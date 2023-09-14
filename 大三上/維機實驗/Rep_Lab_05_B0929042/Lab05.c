/*******************************************************************************
 *
 * the LCD infrastructure module
 *
 ******************************************************************************/

#include "C8051F040.h"
#include "LCD.h"

char LCD_status;

void
LCD_PortConfig ()
{
	//initialize SFR setup page
	SFRPAGE = CONFIG_PAGE;                 // Switch to configuration page

	//setup the cross-bar and configure the I/O ports
	XBR2 = 0xc0;
	P3MDOUT = 0x3f;
	P1MDIN = 0xff;

	//set to normal mode
	SFRPAGE = LEGACY_PAGE;
}//end of function LCD_PortConfig ()

/****************
The delay_lcd seem to be not long enough. 
Try to change the default value as 10000...
****************/
unsigned int delay_lcd=10000;

void
LCD_Delay ()
{
	int i;
	for (i=0;i<delay_lcd;i++); // wait for a long enough time...
}



int button_detect () {
	int key_hold = 0 ;
	int key_release = 0;
	int N = 100, count = N, key = 0;

	P1 = 0;
	do {
		key_hold = P1;  //assign input port P1 to key_hold
	} while (!key_hold);//if not press,keep waiting until key press
	
	//Stage 2: wait for key released;
	while (!key_release) { //while key is pressed
		// detect which way
		key_hold = P1;
		if (P1 == 0x01){	//2^0
			key_hold = 1;
			key = 1;
		}
		else if(P1 == 0x02){ //2^1
			key_hold = 1;
			key = 2;
		}
		else if(P1 == 0x04){ //2^2
			key_hold = 1;
			key = 3;
		}
		else if(P1 == 128){
			key_hold = 1;
			key = 7;
		}

		//detect whether press a period of time
		if (key_hold) {
			count = N;	//set stable time
		}
		else {
			count--;
			if (count==0) {
				key_release = 1;
			}
		}
	}//Stage 2: wait for key released
	return key;
}//end of function button_detect ()

void
LCD_SendCommand (char cmd);

void
LCD_Init ()
{
	LCD_SendCommand (0x02);      // Initialize as 4-bit mode
	LCD_SendCommand (0x28);		//Display function: 2 rows for 4-bit data, small 
	LCD_SendCommand (0x0e);		//display and curson ON, curson blink off
	LCD_SendCommand (0x01);		//clear display, cursor to home
	LCD_SendCommand (0x10);		//cursor shift left
	LCD_SendCommand (0x06);		//cursor increment, shift off
}

void
LCD_Status_SetRS ()
{
	LCD_status = LCD_status | 1;
}

void
LCD_Status_ClearRS ()
{
	LCD_status = LCD_status & 0xfe;
}

void
LCD_Status_SetWord (char word)
{
	word = word & 0x0f; //0x0f display, cursor, blink on
	LCD_status = LCD_status & 0x03;
	LCD_status = LCD_status | (word<<2);
}

void
LCD_Status_SetEnable ()
{
	LCD_status = LCD_status | 0x02; //cursor to home
}


void
LCD_Status_ClearEnable ()
{
	LCD_status = LCD_status & 0xfd;
}


void
LCD_SendCommand (char cmd)
{
	LCD_Status_ClearRS ();	//rs = 0

	///send the higher half
	LCD_Status_SetWord ((cmd>>4) & 0x0f); 
	LCD_Status_SetEnable ();	//en = 1
	P3 = LCD_status;
	LCD_Delay ();
	LCD_Status_ClearEnable ();	//en = 0
	P3 = LCD_status;
	LCD_Delay ();

	///send the lower half
	LCD_Status_SetWord (cmd&0x0f);
	LCD_Status_SetEnable ();
	P3 = LCD_status;
	LCD_Delay ();
	LCD_Status_ClearEnable ();
	P3 = LCD_status;
	LCD_Delay ();
}

void
LCD_SendData (char dat)
{
	LCD_Status_SetRS ();	//rs = 1

	///send the higher(left) half
	LCD_Status_SetWord ((dat>>4) & 0x0f);
	LCD_Status_SetEnable ();	//prepare the status word,en = 1
	P3 = LCD_status;	//send out the status word
	LCD_Delay ();
	LCD_Status_ClearEnable ();	//prepare the status word,en = 0
	P3 = LCD_status;	//send out the status word
	LCD_Delay ();

	///send the lower(right) half
	LCD_Status_SetWord (dat&0x0f);
	LCD_Status_SetEnable ();
	P3 = LCD_status;
	LCD_Delay ();
	LCD_Status_ClearEnable ();
	P3 = LCD_status;
	LCD_Delay ();
}

void
LCD_PrintString (char* str)
{
	int i;

	for (i=0; str[i]!=0; i++) {
		LCD_SendData (str[i]);
	}//for i
}


void
LCD_ClearScreen ()
{
	LCD_SendCommand (0x01);	//clear display screen
}


void
Shutup_WatchDog ()
{
	WDTCN = 0xde;
	WDTCN = 0xad;
}//end of function Shutup_WatchDog


void
main ()
{
	int i = 0, key, line = 0;
	char txt[16];
	Shutup_WatchDog ();
	LCD_PortConfig ();
	LCD_Init ();
	LCD_ClearScreen ();
	
	
	while(1){
		key = button_detect ();
		if (key == 1){
			LCD_SendData ('A');
			if(line!=0)txt[i] = 'A';
		}
		if (key == 2){
			LCD_SendData ('B');
			if(line!=0)txt[i] = 'B';
		}
		if (key == 3){
			LCD_SendData ('C');
			if(line!=0)txt[i] = 'C';
		}
		if (key == 7){
			if(line == 0){
				LCD_SendCommand (0xc0);
			}
			else if(line == 	1){
				LCD_ClearScreen ();
				LCD_PrintString(txt);
				for(i=0;txt[i]!=0;i++){
					txt[i] = 0;
				}
			}
			line = (line + 1) % 2;
		}
		
		if(key != 0){
			i += 1;
			if (i>16)
			{
				LCD_SendCommand (0xc0);
				i = 0;
				line = (line + 1)% 2;
			}
			
		}
	}

}