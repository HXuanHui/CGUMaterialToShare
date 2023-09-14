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

void Timer0_ISR ();
void Timer1_ISR ();

int song[8];

int
main ()
{
	Config ();
	status = 0x00;
	count = 0;
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

	TH0 = 0xff; //set interupt every 0.1 msec
	TL0 = 0xfe;
}//end of function Timer0_ISR

int countSec=0;
int i=0;
void
Timer1_ISR () interrupt 3 //play song
{
	countSec++;	
	if (countSec==10000) {
		countSec = 0;
		half_period = song[i];
		i=(i+1)%8;
	}
	
	TH1 = 0xff; //set interupt every 0.1 msec
	TL1 = 0xfe;
}//end of function Timer0_ISR




