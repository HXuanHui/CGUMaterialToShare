/*******************************************************************************
 *
 * file: [glcd.c]
 *
 ******************************************************************************/

#include "C8051F040.h"
#include "glcd.h"
#include "LCD.h"

/*******************************************************************************
 *
 * functions for configuring the hardware
 *
 ******************************************************************************/

//LCD
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

unsigned int delay_lcd=10000;

void
LCD_Delay ()
{
	int i;
	for (i=0;i<delay_lcd;i++); // wait for a long enough time...
}

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

//GLCD
char P2_CWORD_TEMPLATE=0x21;
void
set_GLCD_WriteMode ()
{
	P4MDOUT = 0xff;
}//end of function set_GLCD_WriteMode


void
set_GLCD_ReadMode ()
{
	P4MDOUT = 0x00;
	P4 = 0xff;
}//end of function set_GLCD_ReadMode

void
system_init_config ()
{
	//turn-off the watch-dog timer
	WDTCN = 0xde;
	WDTCN = 0xad;

	//initialize SFR setup page
	SFRPAGE = CONFIG_PAGE;		// Switch to configuration page

	//setup the cross-bar and configure the I/O ports
	XBR2 = 0xc0;
	P2MDOUT = 0xff;
	P0MDOUT = 0xff;
}//end of function system_init_config




/*******************************************************************************
 *
 * functions to drive hardware signals
 *
 ******************************************************************************/

void
GLCD_delay ()
{
	int i;
	for (i=0;i<10;i++);
}//end of function GLCD_delay

void
GLCD_Write (char P2_cword, char P4_cword)
{
	char P2_cword_rep;

	P2_cword_rep = P2_cword;
	set_GLCD_WriteMode ();
	GLCD_delay ();

	P2_cword_rep = P2_cword_rep & (~P2_E);	//clear E bit
	P2 = P2_cword_rep;
	P4 = P4_cword;
	GLCD_delay ();

	P2_cword_rep = P2_cword_rep | P2_E;		//set E bit
	P2 = P2_cword_rep;
	GLCD_delay ();

	P2_cword_rep = P2_cword_rep & (~P2_E);	//clear E bit
	P2 = P2_cword_rep;
	GLCD_delay ();
	P0 = P2_cword_rep; // nien debug
	
}//end of function GLCD_Write

char
GLCD_Read (char P2_cword)
{
	char status;
	char P2_cword_rep;

	P2_cword_rep = P2_cword;
	set_GLCD_ReadMode ();
	GLCD_delay ();

	P2_cword_rep = P2_cword_rep & (~P2_E);		//clear E bit
	P2 = P2_cword_rep;
	GLCD_delay ();

	P2_cword_rep = P2_cword_rep | P2_E;			//set E bit  
	P2 = P2_cword_rep;
	GLCD_delay ();

	status = P4;

	P2_cword_rep = P2_cword_rep & (~P2_E);		//clear E bit
	P2 = P2_cword_rep;
	GLCD_delay ();

	return status;
}//end of function GLCD_Read



/*******************************************************************************
 *
 * GLCD read operations
 *
 ******************************************************************************/

char
GLCD_ReadStatus ()
{
	char P2_cword;
	char status;

	P2_cword = P2_CWORD_TEMPLATE;
	P2_cword = P2_cword & (~P2_RS);
	P2_cword = P2_cword | (P2_RW);
	status = GLCD_Read (P2_cword);

	return status;
}//end of function GLCD_ReadStatus


char
GLCD_ReadData ()
{
	char P2_cword;
	char dat;

	P2_cword = P2_CWORD_TEMPLATE;
	P2_cword = P2_cword | (P2_RS);
	P2_cword = P2_cword | (P2_RW);
	dat = GLCD_Read (P2_cword);

	return dat;
}//end of function GLCD_ReadData

int
GLCD_IsBusy ()
{
	char status;

	status = GLCD_ReadStatus ();
	if (status&P4_Busy)
		return 1;
	else
		return 0;
}//end of function GLCD_IsBusy


int
GLCD_IsReset ()
{
	char status;

	status = GLCD_ReadStatus ();
	if (status & P4_Reset)
		return 1;
	else
		return 0;
}//end of function GLCD_IsReset


int
GLCD_IsON ()
{
	return !GLCD_IsOFF ();
}//end of function GLCD_IsON


int
GLCD_IsOFF ()
{
	char status;

	status = GLCD_ReadStatus ();
	if (status & P4_Status_On)
		return 1;
	else
		return 0;
}//end of function GLCD_IsOFF


/*******************************************************************************
 *
 * functions to send commands and data to GLCD
 *
 ******************************************************************************/

void
Set_Xaddr (char x)
{
	char P2_cword, P4_cword;

	///prepare control words
	P2_cword = P2_CWORD_TEMPLATE;
	P2_cword = P2_cword & (~P2_RS);		//clear RS bit
	P2_cword = P2_cword & (~P2_RW);		//clear RW bit
	P4_cword = P4_Set_Xaddr_TMPL;
	P4_cword = P4_cword | (x & 0x07);

	///flush out control signals
	while (GLCD_IsBusy());
	GLCD_Write (P2_cword, P4_cword);
}//end of function Set_Xaddr


void
Set_Yaddr (char y)
{
	char P2_cword, P4_cword;

	///prepare control words
	P2_cword = P2_CWORD_TEMPLATE;
	P2_cword = P2_cword & (~P2_RS);		//clear RS bit
	P2_cword = P2_cword & (~P2_RW);		//clear RW bit
	P4_cword = P4_Set_Yaddr_TMPL;
	P4_cword = P4_cword | (y & 0x3f);

	///flush out control signals
	while (GLCD_IsBusy());
	GLCD_Write (P2_cword, P4_cword);
}//end of function Set_Yaddr


void
Set_DisplayStartLine (char z)
{
	char P2_cword, P4_cword;

	///prepare control words
	P2_cword = P2_CWORD_TEMPLATE;
	P2_cword = P2_cword & (~P2_RS);		//clear RS bit
	P2_cword = P2_cword & (~P2_RW);		//clear RW bit
	P4_cword = P4_Set_Zaddr_TMPL;
	P4_cword = P4_cword | (z & 0x3f);
	///flush out control signals
	while (GLCD_IsBusy());
	GLCD_Write (P2_cword, P4_cword);
}//end of function Set_DisplayStartLine


void
Send_Data (char pattern)
{
	char P2_cword, P4_cword;

	///prepare control words
	P2_cword = P2_CWORD_TEMPLATE;
	P2_cword = P2_cword | (P2_RS);		//set RS bit
	P2_cword = P2_cword & (~P2_RW);		//clear RW bit
	P4_cword = pattern;

	///flush out control signals
	while (GLCD_IsBusy());
	GLCD_Write (P2_cword, P4_cword);
}//end of function Send_Data

void
Set_DisplayOn (int mode)
{
	char P2_cword, P4_cword;
	if(mode == 0){
		P2_CWORD_TEMPLATE = 0x21;
		// P2_cword = P2_CWORD_TEMPLATE | P2_CS1;	//set right
	}
	if(mode == 1){
		P2_CWORD_TEMPLATE = 0x22;
		// P2_cword = P2_CWORD_TEMPLATE | P2_CS2;	//set left
	}
	///prepare control words
	P2_cword = P2_CWORD_TEMPLATE  ;	
	P2_cword = P2_cword & (~P2_RS);		//set RS bit
	P2_cword = P2_cword & (~P2_RW);		//clear RW bit
	P4_cword = P4_Set_Display_TMPL;
	P4_cword = P4_cword | P4_Display_On;	//set display ON bit

	///flush out control signals
	while (GLCD_IsBusy());
	GLCD_Write (P2_cword, P4_cword);
}//end of function Set_DisplayOn


void
Set_DisplayOff ()
{
	char P2_cword, P4_cword;

	///prepare control words
	P2_cword = P2_CWORD_TEMPLATE;
	P2_cword = P2_cword & (~P2_RS);		//set RS bit
	P2_cword = P2_cword & (~P2_RW);		//clear RW bit
	P4_cword = P4_Set_Display_TMPL;
	P4_cword = P4_cword & (~P4_Display_On);	//clear display ON bit

	///flush out control signals
	while (GLCD_IsBusy());
	GLCD_Write (P2_cword, P4_cword);
}//end of function Set_DisplayOff


void
GLCD_Reset ()
{
	char P2_cword, P4_cword;

	set_GLCD_WriteMode ();

	P2_cword = P2_CWORD_TEMPLATE;
	P4_cword = 0;

	P2_cword = P2_cword | P2_RST;		//set reset bit
	GLCD_Write (P2_cword, P4_cword);

	P2_cword = P2_cword & (~P2_RST);	//clear reset bit
	GLCD_Write (P2_cword, P4_cword);

	P2_cword = P2_cword | P2_RST;		//set reset bit
	GLCD_Write (P2_cword, P4_cword);

	while (GLCD_IsReset());
}//end of function GLCD_Reset


/*******************************************************************************
 *
 * Drawing functions that you implement
 *
 ******************************************************************************/


void draw()
{
	int i,j;
	Set_DisplayStartLine (0);
	Set_Yaddr (0);
	for(j=0;j<8;j++){
		Set_Xaddr (j);
		for (i=0;i<64;i++)
			Send_Data (0x00);
	}
}

void initial(){
	Set_DisplayStartLine (0);
	Set_DisplayOn (0);
	draw();
	Set_DisplayOn (1);
	draw();
}

/*******************************************************************************
 *
 * button detect
 *
 ******************************************************************************/
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
		else if(P1 == 0x08){ //2^3
			key_hold = 1;
			key = 4;
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
/*******************************************************************************
 *
 * the snack
 *
 ******************************************************************************/
void 
drawByteLeft(int x,int y,int bits){
	int i;
	Set_DisplayStartLine (0);
	Set_DisplayOn (1);
	Set_Xaddr(x);
	Set_Yaddr(y*8);
	for(i = y*8;i < y*8+8; i++){
		Send_Data (bits);
	}
}
void 
drawByteRight(int x,int y,int bits){
	int i;
	Set_DisplayStartLine (0);
	Set_DisplayOn (0);
	Set_Xaddr(x);
	Set_Yaddr((y-8)*8);
	for(i = (y-8)*8;i < (y-8)*8+8; i++){
		Send_Data (bits);
	}
}
int snackLen = 2;
//defualt head & bottom
int snackX[3] = {7,7};
int snackY[3] = {15,14};
int head = 1,buttom=0;
int fx,fy;
int score = 0;
void food(){
	fx = rand()%8+0;
	fy = rand()%16+0;
	if(fy<8) {// draw left
		drawByteLeft(fx,fy,0x3c);
	}
	if(fy>=8 && fy<16) {// draw right
		drawByteRight(fx,fy,0x3c);
	}
}
void snack(int snackDest){
	int x,y; //0 <= x <= 7 ; 0 <= y <= 15
	int i = 0;
	if(snackX[buttom] > 0 && snackY[buttom] > 0){
		x = snackX[buttom];
		y = snackY[buttom];
		if(y<8) {// draw left
			drawByteLeft(x,y,0x00);
		}
		if(y>=8 && y<16) {// draw right
			drawByteRight(x,y,0x00);
		}
	}
	buttom = head;
	if(snackDest == 1){ // up 
		head = (head + 1)%2;
		snackX[head] = snackX[buttom] - 1;
		snackY[head] = snackY[buttom];
	}
	else if(snackDest == 2){ // down
		head = (head + 1)%2;
		snackX[head] = snackX[buttom] + 1;
		snackY[head] = snackY[buttom];
	}
	else if(snackDest == 3){ // left
		head = (head + 1)%2;
		snackX[head] = snackX[buttom];
		snackY[head] = snackY[buttom] - 1;
	}
	else if(snackDest == 4){ // right
		head = (head + 1)%2;
		snackX[head] = snackX[buttom];
		snackY[head] = snackY[buttom] + 1;
	}
	if(snackX[head] >= 0 && snackX[head] < 8 && snackY[head] >= 0 && snackY[head] < 16){
		x = snackX[head];
		y = snackY[head];
		if (x==fx && y==fy){
			score += 1;
			food();
		}
		if(y<8) {// draw left
			drawByteLeft(x,y,0xff);
		}
		if(y>=8 && y<16) {// draw right
			drawByteRight(x,y,0xff);
		}
	}
	else if(snackX[head] < 0 || snackX[head] >= 8 || snackY[head] < 0 || snackY[head] >= 16){
		initial();
		food();
		snackX[0] = 7;
		snackX[1] = 7;
		snackY[0] = 15;
		snackY[1] = 14;
	}
	
}

/*******************************************************************************
 *
 * the main drawing function
 *
 ******************************************************************************/
void
main (){
    int key;
	system_init_config ();
	Shutup_WatchDog ();
	GLCD_Reset ();
	// initial canvas
	initial();
	snack(0);
	food();
	while (1){
		P4 = score;
		key = button_detect ();
		if(key == 1){ //up
			snack(1);
		}
		else if(key == 2){ //down
			snack(2);
		}
		else if(key == 3){ //left
			snack(3);
		}
		else if(key == 4){ //right
			snack(4);
		}
	}
}//end of function main