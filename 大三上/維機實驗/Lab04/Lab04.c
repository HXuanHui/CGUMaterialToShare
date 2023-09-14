#include "C8051F040.h"

void Port_Configuration (){
	XBR2 = 0xc0;
	P3MDIN = 0xff;
	P2MDOUT = 0xff;
}//end of function Port_Configuration

void Default_Config () {
	//turn-off watch-dog timer
	// disable watchdog timer
	WDTCN = 0xde;
	WDTCN = 0xad;

	//initialize SFR setup page
	SFRPAGE = CONFIG_PAGE;                 // Switch to configuration page
	Port_Configuration ();

	//set to normal mode
	SFRPAGE = LEGACY_PAGE;
}//end of function Default_Config


/****************
There are something wrong with the function below!
Please see the following hints:
1. See the error message and fix those errors
2. The initiailization of N
3. The initialization of P1
****************/
int button_detect () {
	int key_hold = 0 ;
	int key_release = 0;
	int N = 100, count = N;
	int ori = 0;

	P3 = 0;
	do {
		key_hold = P3;  //assign input port P1 to key_hold
	} while (!key_hold);//if not press,keep waiting until key press
	//once key pressed, jump out

	//Stage 2: wait for key released;
	while (!key_release) { //while key is pressed
		// detect which way
		key_hold = 0 ;
		if (P3 == 1){	//2^0
			key_hold = 1;
			ori = 1;
		}
		else if(P3 == 2){ //2^1
			key_hold = 1;
			ori = 2;
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
	return ori;
}//end of function button_detect ()


int main () {
	int status;
	int ori;

	Default_Config(); //set config

	status = 1;
	P2 = status;

	while (1) {
		ori = button_detect ();

		if(ori == 1)status *= 2 ;
		else if (ori == 2) status /=  2;
		else status = status;

		if (status>128) status = 1;
		if(status<1) status = 128;

		P2 = status;
	}//end while (1)
}//end of function main '