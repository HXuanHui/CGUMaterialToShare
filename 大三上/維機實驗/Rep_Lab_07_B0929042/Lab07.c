#include "C8051F040.h"

void
Port_Configuration ()
{
	//initialize SFR setup page
	SFRPAGE = CONFIG_PAGE;                 // Switch to configuration page

	//setup the cross-bar and configure the I/O ports
	XBR2 = 0xc0;
	P3MDIN = 0xff;
	P2MDOUT = 0xff;

	//set to normal mode
	SFRPAGE = LEGACY_PAGE;
}//end of function Port_Configuration

void
Timer_Configuration ()
{
	TMOD = 0x11;
	TCON = 0x50;
	CKCON = 0x10;

	IE = 0x8a;
	TL0 = 0xfd;
	TH0 = 0xeb;
	TL1 = 0x35;
	TH1 = 0xff;
}//end of function Timer_Configuration

void
Config ()
{
	//turn-off watch-dog timer
	WDTCN = 0xde;
	WDTCN = 0xad;

	SFRPAGE = CONFIG_PAGE;

	OSCICN = 0x83;
	CLKSEL = 0x00;

	Port_Configuration ();
	Timer_Configuration ();
}//end of function Default_Config

unsigned char status;
int count;
int half_period;
int countSec;
int tone;
int i=0;

void Timer0_ISR ();
void Timer1_ISR ();

int song[8];
int sheet[30] = {0,1,2,0,0,1,2,0,2,3,4,4,2,3,4,4,4,5,4,3,2,0,4,5,4,3,2,0};
// {0,0,1,2,2,1,0,1,2,0,2,2,3,4,4,3,2,3,4,2,7,6,5,4,2,7,6,5,4,3};

int
main ()
{
	Config ();
	status = 0x00;
	count = 0;
	countSec = 0;
	//half_period = 12;//set half period
	song[0]=19;
	song[1]=17;
	song[2]=15;
	song[3]=14;
	song[4]=12;
	song[5]=11;
	song[6]=10;
	song[7]=9;


	while (1) {
		P2 = status;
	}//end while (1)
}//end of function main

void
Timer0_ISR () interrupt 1 //play tone by vary period
{
	count++;	//supposed 100us-per-count

	if (count==half_period) {
		count = 0;
		status = ~status;
	}

	TH0 = 0xff; 
	TL0 = 0x35;
}//end of function Timer0_ISR

void
Timer1_ISR () interrupt 3 //play song, change tone per second
{
	countSec++;	
	if (countSec==30000) {
		countSec = 0;
		count = 0;
		tone = sheet[i];
		half_period = song[tone];
		i=(i+1)%28;
	}
	TH1 = 0xff; //set interupt every 0.1 msec
	TL1 = 0x00;
}//end of function Timer1_ISR




