//-----------------------------------------------------------------------------
// C8051F040_defs.h
//-----------------------------------------------------------------------------
// Copyright 2008 Silicon Laboratories, Inc.
// http://www.silabs.com
//
// Program Description:
//
// Register/bit definitions for the C8051F04x/'F04x family.
// **Important Note**: The si_toolchain.h header file should be included
// before including this header file.
//
// Target:         C8051F04x, 'F04x
// Tool chain:     Keil
// Command Line:   None
//
// Release 1.5 - 20 AUG 2012 (TP)
//    -Added #define for _XPAGE to provide support for SDCC memory paging
//     (pdata)
// Release 1.4 - 22 July 2008 (ES)
//    -Updated SCON1, IP, ADC0CN, ADC2CN bit definitions
// Release 1.3
//    -29 FEB 2008
//    -Made compiler independant by AS
//    -Added 16-bit sfr definitions
//
//-----------------------------------------------------------------------------
// Header File Preprocessor Directive
//-----------------------------------------------------------------------------

#ifndef C8051F040_DEFS_H
#define C8051F040_DEFS_H

//-----------------------------------------------------------------------------
// Byte Registers
//-----------------------------------------------------------------------------

SI_SFR(P0, 0x80);           // PORT 0
SI_SFR(SP, 0x81);           // STACK POINTER
SI_SFR(DPL, 0x82);          // DATA POINTER - LOW BYTE
SI_SFR(DPH, 0x83);          // DATA POINTER - HIGH BYTE
SI_SFR(SFRPAGE, 0x84);      // SFR PAGE SELECT
SI_SFR(SFRNEXT, 0x85);      // SFR STACK NEXT PAGE
SI_SFR(SFRLAST, 0x86);      // SFR STACK LAST PAGE
SI_SFR(PCON, 0x87);         // POWER CONTROL
SI_SFR(TCON, 0x88);         // TIMER CONTROL
SI_SFR(CPT0CN, 0x88);       // COMPARATOR 0 CONTROL
SI_SFR(CPT1CN, 0x88);       // COMPARATOR 1 CONTROL
SI_SFR(CPT2CN, 0x88);       // COMPARATOR 2 CONTROL
SI_SFR(TMOD, 0x89);         // TIMER MODE
SI_SFR(CPT0MD, 0x89);       // COMPARATOR 0 MODE
SI_SFR(CPT1MD, 0x89);       // COMPARATOR 1 MODE
SI_SFR(CPT2MD, 0x89);       // COMPARATOR 2 MODE
SI_SFR(TL0, 0x8A);          // TIMER 0 - LOW BYTE
SI_SFR(OSCICN, 0x8A);       // INTERNAL OSCILLATOR CONTROL
SI_SFR(TL1, 0x8B);          // TIMER 1 - LOW BYTE
SI_SFR(OSCICL, 0x8B);       // INTERNAL OSCILLATOR CALIBRATION
SI_SFR(TH0, 0x8C);          // TIMER 0 - HIGH BYTE
SI_SFR(OSCXCN, 0x8C);       // EXTERNAL OSCILLATOR CONTROL
SI_SFR(TH1, 0x8D);          // TIMER 1 - HIGH BYTE
SI_SFR(CKCON, 0x8E);        // TIMER 0/1 CLOCK CONTROL
SI_SFR(PSCTL, 0x8F);        // FLASH WRITE/ERASE CONTROL
SI_SFR(P1, 0x90);           // PORT 1
SI_SFR(SSTA0, 0x91);        // UART 0 STATUS
SI_SFR(SFRPGCN, 0x96);      // SFR PAGE CONTROL
SI_SFR(CLKSEL, 0x97);       // SYSTEM CLOCK SELECT
SI_SFR(SCON0, 0x98);        // UART 0 CONTROL
SI_SFR(SCON1, 0x98);        // UART 1 CONTROL
SI_SFR(SBUF0, 0x99);        // UART 0 BUFFER
SI_SFR(SBUF1, 0x99);        // UART 1 BUFFER
SI_SFR(SPI0CFG, 0x9A);      // SPI 0 CONFIGURATION
SI_SFR(SPI0DAT, 0x9B);      // SPI 0 DATA
SI_SFR(P4MDOUT, 0x9C);      // PORT 4 OUTPUT MODE
SI_SFR(SPI0CKR, 0x9D);      // SPI 0 CLOCK RATE CONTROL
SI_SFR(P5MDOUT, 0x9D);      // PORT 5 OUTPUT MODE
SI_SFR(P6MDOUT, 0x9E);      // PORT 6 OUTPUT MODE
SI_SFR(P7MDOUT, 0x9F);      // PORT 7 OUTPUT MODE
SI_SFR(P2, 0xA0);           // PORT 2
SI_SFR(EMI0TC, 0xA1);       // EMIF TIMING CONTROL
SI_SFR(EMI0CN, 0xA2);       // EMIF CONTROL
SI_SFR(EMI0CF, 0xA3);       // EMIF CONFIGURATION
SI_SFR(P0MDOUT, 0xA4);      // PORT 0 OUTPUT MODE
SI_SFR(P1MDOUT, 0xA5);      // PORT 1 OUTPUT MODE
SI_SFR(P2MDOUT, 0xA6);      // PORT 2 OUTPUT MODE CONFIGURATION
SI_SFR(P3MDOUT, 0xA7);      // PORT 3 OUTPUT MODE CONFIGURATION
SI_SFR(IE, 0xA8);           // INTERRUPT ENABLE
SI_SFR(SADDR0, 0xA9);       // UART 0 SLAVE ADDRESS
SI_SFR(SADDR1, 0xA9);       // UART 1 SLAVE ADDRESS
SI_SFR(P1MDIN, 0xAD);       // PORT 1 INPUT MODE
SI_SFR(P2MDIN, 0xAE);       // PORT 2 INPUT MODE
SI_SFR(P3MDIN, 0xAF);       // PORT 3 INPUT MODE
SI_SFR(P3, 0xB0);           // PORT 3
SI_SFR(FLSCL, 0xB7);        // FLASH TIMING PRESCALAR
SI_SFR(FLACL, 0xB7);        // FLASH ACCESS LIMIT
SI_SFR(IP, 0xB8);           // INTERRUPT PRIORITY
SI_SFR(SADEN0, 0xB9);       // UART 0 SLAVE ADDRESS MASK
SI_SFR(AMX2CF, 0xBA);       // ADC 2 MUX CONFIGURATION
SI_SFR(AMX0PRT, 0xBD);      // ADC 0 MUX PORT PIN SELECT REGISTER
SI_SFR(AMX0CF, 0xBA);       // ADC 0 CONFIGURATION REGISTER
SI_SFR(AMX0SL, 0xBB);       // ADC 0 AND ADC 1 MODE SELECTION
SI_SFR(AMX2SL, 0xBB);       // ADC 2 MUX CHANNEL SELECTION
SI_SFR(ADC0CF, 0xBC);       // ADC 0 CONFIGURATION
SI_SFR(ADC2CF, 0xBC);       // ADC 2 CONFIGURATION
SI_SFR(ADC0L, 0xBE);        // ADC 0 DATA - LOW BYTE
SI_SFR(ADC2, 0xBE);         // ADC 2 DATA - LOW BYTE
SI_SFR(ADC0H, 0xBF);        // ADC 0 DATA - HIGH BYTE
SI_SFR(SMB0CN, 0xC0);       // SMBUS 0 CONTROL
SI_SFR(CAN0STA, 0xC0);      // CAN 0 STATUS
SI_SFR(SMB0STA, 0xC1);      // SMBUS 0 STATUS
SI_SFR(SMB0DAT, 0xC2);      // SMBUS 0 DATA
SI_SFR(SMB0ADR, 0xC3);      // SMBUS 0 SLAVE ADDRESS
SI_SFR(ADC0GTL, 0xC4);      // ADC 0 GREATER-THAN REGISTER - LOW BYTE
SI_SFR(ADC2GT, 0xC4);       // ADC 2 GREATER-THAN REGISTER - LOW BYTE
SI_SFR(ADC0GTH, 0xC5);      // ADC 0 GREATER-THAN REGISTER - HIGH BYTE
SI_SFR(ADC0LTL, 0xC6);      // ADC 0 LESS-THAN REGISTER - LOW BYTE
SI_SFR(ADC2LT, 0xC6);       // ADC 2 LESS-THAN REGISTER - LOW BYTE
SI_SFR(ADC0LTH, 0xC7);      // ADC 0 LESS-THAN REGISTER - HIGH BYTE
SI_SFR(TMR2CN, 0xC8);       // TIMER 2 CONTROL
SI_SFR(TMR3CN, 0xC8);       // TIMER 3 CONTROL
SI_SFR(TMR4CN, 0xC8);       // TIMER 4 CONTROL
SI_SFR(P4, 0xC8);           // PORT 4
SI_SFR(TMR2CF, 0xC9);       // TIMER 2 CONFIGURATION
SI_SFR(TMR3CF, 0xC9);       // TIMER 3 CONFIGURATION
SI_SFR(TMR4CF, 0xC9);       // TIMER 4 CONFIGURATION
SI_SFR(RCAP2L, 0xCA);       // TIMER 2 CAPTURE REGISTER - LOW BYTE
SI_SFR(RCAP3L, 0xCA);       // TIMER 3 CAPTURE REGISTER - LOW BYTE
SI_SFR(RCAP4L, 0xCA);       // TIMER 4 CAPTURE REGISTER - LOW BYTE
SI_SFR(RCAP2H, 0xCB);       // TIMER 2 CAPTURE REGISTER - HIGH BYTE
SI_SFR(RCAP3H, 0xCB);       // TIMER 3 CAPTURE REGISTER - HIGH BYTE
SI_SFR(RCAP4H, 0xCB);       // TIMER 4 CAPTURE REGISTER - HIGH BYTE
SI_SFR(TMR2L, 0xCC);        // TIMER 2 - LOW BYTE
SI_SFR(TMR3L, 0xCC);        // TIMER 3 - LOW BYTE
SI_SFR(TMR4L, 0xCC);        // TIMER 4 - LOW BYTE
SI_SFR(TMR2H, 0xCD);        // TIMER 2 - HIGH BYTE
SI_SFR(TMR3H, 0xCD);        // TIMER 3 - HIGH BYTE
SI_SFR(TMR4H, 0xCD);        // TIMER 4 - HIGH BYTE
SI_SFR(SMB0CR, 0xCF);       // SMBUS 0 CLOCK RATE
SI_SFR(PSW, 0xD0);          // PROGRAM STATUS WORD
SI_SFR(REF0CN, 0xD1);       // VOLTAGE REFERENCE 0 CONTROL
SI_SFR(DAC0L, 0xD2);        // DAC 0 REGISTER - LOW BYTE
SI_SFR(DAC1L, 0xD2);        // DAC 1 REGISTER - LOW BYTE
SI_SFR(DAC0H, 0xD3);        // DAC 0 REGISTER - HIGH BYTE
SI_SFR(DAC1H, 0xD3);        // DAC 1 REGISTER - HIGH BYTE
SI_SFR(DAC0CN, 0xD4);       // DAC 0 CONTROL
SI_SFR(DAC1CN, 0xD4);       // DAC 1 CONTROL
SI_SFR(HVA0CN, 0xD6);       // HVDA CONTROL REGISTER
SI_SFR(PCA0CN, 0xD8);       // PCA 0 COUNTER CONTROL
SI_SFR(CAN0DATL, 0xD8);     // CAN 0 DATA - LOW BYTE
SI_SFR(P5, 0xD8);           // PORT 5
SI_SFR(PCA0MD, 0xD9);       // PCA 0 COUNTER MODE
SI_SFR(CAN0DATH, 0xD9);     // CAN 0 DATA - HIGH BYTE
SI_SFR(PCA0CPM0, 0xDA);     // PCA 0 MODULE 0 CONTROL
SI_SFR(CAN0ADR, 0xDA);      // CAN 0 ADDRESS
SI_SFR(PCA0CPM1, 0xDB);     // PCA 0 MODULE 1 CONTROL
SI_SFR(CAN0TST, 0xDB);      // CAN 0 TEST
SI_SFR(PCA0CPM2, 0xDC);     // PCA 0 MODULE 2 CONTROL
SI_SFR(PCA0CPM3, 0xDD);     // PCA 0 MODULE 3 CONTROL
SI_SFR(PCA0CPM4, 0xDE);     // PCA 0 MODULE 4 CONTROL
SI_SFR(PCA0CPM5, 0xDF);     // PCA 0 MODULE 5 CONTROL
SI_SFR(ACC, 0xE0);          // ACCUMULATOR
SI_SFR(PCA0CPL5, 0xE1);     // PCA 0 MODULE 5 CAPTURE/COMPARE - LOW BYTE
SI_SFR(XBR0, 0xE1);         // CROSSBAR CONFIGURATION REGISTER 0
SI_SFR(PCA0CPH5, 0xE2);     // PCA 0 MODULE 5 CAPTURE/COMPARE - HIGH BYTE
SI_SFR(XBR1, 0xE2);         // CROSSBAR CONFIGURATION REGISTER 1
SI_SFR(XBR2, 0xE3);         // CROSSBAR CONFIGURATION REGISTER 2
SI_SFR(XBR3, 0xE4);         // CROSSBAR CONFIGURATION REGISTER 3
SI_SFR(EIE1, 0xE6);         // EXTERNAL INTERRUPT ENABLE 1
SI_SFR(EIE2, 0xE7);         // EXTERNAL INTERRUPT ENABLE 2
SI_SFR(ADC0CN, 0xE8);       // ADC 0 CONTROL
SI_SFR(ADC2CN, 0xE8);       // ADC 2 CONTROL
SI_SFR(P6, 0xE8);           // PORT 6
SI_SFR(PCA0CPL2, 0xE9);     // PCA 0 MODULE 2 CAPTURE/COMPARE - LOW BYTE
SI_SFR(PCA0CPH2, 0xEA);     // PCA 0 MODULE 2 CAPTURE/COMPARE - HIGH BYTE
SI_SFR(PCA0CPL3, 0xEB);     // PCA 0 MODULE 3 CAPTURE/COMPARE - LOW BYTE
SI_SFR(PCA0CPH3, 0xEC);     // PCA 0 MODULE 3 CAPTURE/COMPARE - HIGH BYTE
SI_SFR(PCA0CPL4, 0xED);     // PCA 0 MODULE 4 CAPTURE/COMPARE - LOW BYTE
SI_SFR(PCA0CPH4, 0xEE);     // PCA 0 MODULE 4 CAPTURE/COMPARE - HIGH BYTE
SI_SFR(RSTSRC, 0xEF);       // RESET SOURCE
SI_SFR(B, 0xF0);            // B REGISTER
SI_SFR(EIP1, 0xF6);         // EXTERNAL INTERRUPT PRIORITY REGISTER 1
SI_SFR(EIP2, 0xF7);         // EXTERNAL INTERRUPT PRIORITY REGISTER 2
SI_SFR(SPI0CN, 0xF8);       // SPI 0 CONTROL
SI_SFR(CAN0CN, 0xF8);       // CAN 0 CONTROL
SI_SFR(P7, 0xF8);           // PORT 7
SI_SFR(PCA0L, 0xF9);        // PCA 0 TIMER - LOW BYTE
SI_SFR(PCA0H, 0xFA);        // PCA 0 TIMER - HIGH BYTE
SI_SFR(PCA0CPL0, 0xFB);     // PCA 0 MODULE 0 CAPTURE/COMPARE - LOW BYTE
SI_SFR(PCA0CPH0, 0xFC);     // PCA 0 MODULE 0 CAPTURE/COMPARE - HIGH BYTE
SI_SFR(PCA0CPL1, 0xFD);     // PCA 0 MODULE 1 CAPTURE/COMPARE - LOW BYTE
SI_SFR(PCA0CPH1, 0xFE);     // PCA 0 MODULE 1 CAPTURE/COMPARE - HIGH BYTE
SI_SFR(WDTCN, 0xFF);        // WATCHDOG TIMER CONTROL

//-----------------------------------------------------------------------------
// 16-bit Register Definitions (might not be supported by all compilers)
//-----------------------------------------------------------------------------

SI_SFR16(PCA0, 0xF9);        // PCA0 Counter
SI_SFR16(PCA0CP0, 0xFB);     // PCA0 Module 0 Capture/Compare
SI_SFR16(PCA0CP1, 0xFD);     // PCA0 Module 1 Capture/Compare
SI_SFR16(PCA0CP2, 0xE9);     // PCA0 Module 2 Capture/Compare
SI_SFR16(PCA0CP3, 0xEB);     // PCA0 Module 3 Capture/Compare
SI_SFR16(PCA0CP4, 0xED);     // PCA0 Module 4 Capture/Compare
SI_SFR16(PCA0CP5, 0xE1);     // PCA0 Module 5 Capture/Compare
SI_SFR16(CAN0DAT, 0xD8);     // CAN 0 DATA
SI_SFR16(DAC0, 0xD2);        // DAC 0 REGISTER
SI_SFR16(DAC1, 0xD2);        // DAC 1 REGISTER
SI_SFR16(RCAP2, 0xCA);       // TIMER 2 CAPTURE REGISTER
SI_SFR16(RCAP3, 0xCA);       // TIMER 3 CAPTURE REGISTER
SI_SFR16(RCAP4, 0xCA);       // TIMER 4 CAPTURE REGISTER
SI_SFR16(TMR2, 0xCC);        // TIMER 2
SI_SFR16(TMR3, 0xCC);        // TIMER 3
SI_SFR16(TMR4, 0xCC);        // TIMER 4
SI_SFR16(ADC0GT, 0xC4);      // ADC0 Greater-Than Compare
SI_SFR16(ADC0LT, 0xC6);      // ADC0 Less-Than Compare
SI_SFR16(ADC0, 0xBE);        // ADC0 Data
SI_SFR16(DP, 0x82);          // Data Pointer

//-----------------------------------------------------------------------------
// Address Definitions for Bit-addressable Registers
//-----------------------------------------------------------------------------

#define SFR_P0      0x80
#define SFR_TCON    0x88
#define SFR_CPT0CN  0x88
#define SFR_CPT1CN  0x88
#define SFR_CPT2CN  0x88
#define SFR_P1      0x90
#define SFR_SCON0   0x98
#define SFR_SCON1   0x98
#define SFR_P2      0xA0
#define SFR_IE      0xA8
#define SFR_P3      0xB0
#define SFR_IP      0xB8
#define SFR_SMB0CN  0xC0
#define SFR_CAN0STA 0xC0
#define SFR_P4      0xC8
#define SFR_TMR2CN  0xC8
#define SFR_TMR3CN  0xC8
#define SFR_TMR4CN  0xC8
#define SFR_PSW     0xD0
#define SFR_P5      0xD8
#define SFR_PCA0CN  0xD8
#define SFR_ACC     0xE0
#define SFR_P6      0xE8
#define SFR_ADC0CN  0xE8
#define SFR_ADC2CN  0xE8
#define SFR_B       0xF0
#define SFR_SPI0CN  0xF8
#define SFR_CAN0CN  0xF8
#define SFR_P7      0xF8

//-----------------------------------------------------------------------------
// Bit Definitions
//-----------------------------------------------------------------------------

//  TCON  0x88
SI_SBIT(TF1, SFR_TCON, 7);             // TIMER 1 OVERFLOW FLAG
SI_SBIT(TR1, SFR_TCON, 6);             // TIMER 1 ON/OFF CONTROL
SI_SBIT(TF0, SFR_TCON, 5);             // TIMER 0 OVERFLOW FLAG
SI_SBIT(TR0, SFR_TCON, 4);             // TIMER 0 ON/OFF CONTROL
SI_SBIT(IE1, SFR_TCON, 3);             // EXT. INTERRUPT 1 EDGE FLAG
SI_SBIT(IT1, SFR_TCON, 2);             // EXT. INTERRUPT 1 TYPE
SI_SBIT(IE0, SFR_TCON, 1);             // EXT. INTERRUPT 0 EDGE FLAG
SI_SBIT(IT0, SFR_TCON, 0);             // EXT. INTERRUPT 0 TYPE

//  CPT0CN  0x88
SI_SBIT(CP0EN, SFR_CPT0CN, 7);         // COMPARATOR 0 ENABLE
SI_SBIT(CP0OUT, SFR_CPT0CN, 6);        // COMPARATOR 0 OUTPUT
SI_SBIT(CP0RIF, SFR_CPT0CN, 5);        // COMPARATOR 0 RISING EDGE INTERRUPT
SI_SBIT(CP0FIF, SFR_CPT0CN, 4);        // COMPARATOR 0 FALLING EDGE INTERRUPT
SI_SBIT(CP0HYP1, SFR_CPT0CN, 3);       // COMPARATOR 0 POSITIVE HYSTERESIS 1
SI_SBIT(CP0HYP0, SFR_CPT0CN, 2);       // COMPARATOR 0 POSITIVE HYSTERESIS 0
SI_SBIT(CP0HYN1, SFR_CPT0CN, 1);       // COMPARATOR 0 NEGATIVE HYSTERESIS 1
SI_SBIT(CP0HYN0, SFR_CPT0CN, 0);       // COMPARATOR 0 NEGATIVE HYSTERESIS 0

//  CPT1CN  0x88
SI_SBIT(CP1EN, SFR_CPT1CN, 7);         // COMPARATOR 1 ENABLE
SI_SBIT(CP1OUT, SFR_CPT1CN, 6);        // COMPARATOR 1 OUTPUT
SI_SBIT(CP1RIF, SFR_CPT1CN, 5);        // COMPARATOR 1 RISING EDGE INTERRUPT
SI_SBIT(CP1FIF, SFR_CPT1CN, 4);        // COMPARATOR 1 FALLING EDGE INTERRUPT
SI_SBIT(CP1HYP1, SFR_CPT1CN, 3);       // COMPARATOR 1 POSITIVE HYSTERESIS 1
SI_SBIT(CP1HYP0, SFR_CPT1CN, 2);       // COMPARATOR 1 POSITIVE HYSTERESIS 0
SI_SBIT(CP1HYN1, SFR_CPT1CN, 1);       // COMPARATOR 1 NEGATIVE HYSTERESIS 1
SI_SBIT(CP1HYN0, SFR_CPT1CN, 0);       // COMPARATOR 1 NEGATIVE HYSTERESIS 0

//  CPT2CN  0x88
SI_SBIT(CP2EN, SFR_CPT2CN, 7);         // COMPARATOR 2 ENABLE
SI_SBIT(CP2OUT, SFR_CPT2CN, 6);        // COMPARATOR 2 OUTPUT
SI_SBIT(CP2RIF, SFR_CPT2CN, 5);        // COMPARATOR 2 RISING EDGE INTERRUPT
SI_SBIT(CP2FIF, SFR_CPT2CN, 4);        // COMPARATOR 2 FALLING EDGE INTERRUPT
SI_SBIT(CP2HYP1, SFR_CPT2CN, 3);       // COMPARATOR 2 POSITIVE HYSTERESIS 1
SI_SBIT(CP2HYP0, SFR_CPT2CN, 2);       // COMPARATOR 2 POSITIVE HYSTERESIS 0
SI_SBIT(CP2HYN1, SFR_CPT2CN, 1);       // COMPARATOR 2 NEGATIVE HYSTERESIS 1
SI_SBIT(CP2HYN0, SFR_CPT2CN, 0);       // COMPARATOR 2 NEGATIVE HYSTERESIS 0

//  SCON0  0x98
SI_SBIT(SM00, SFR_SCON0, 7);           // UART 0 MODE 0
SI_SBIT(SM10, SFR_SCON0, 6);           // UART 0 MODE 1
SI_SBIT(SM20, SFR_SCON0, 5);           // UART 0 MCE
SI_SBIT(REN0, SFR_SCON0, 4);           // UART 0 RX ENABLE
SI_SBIT(TB80, SFR_SCON0, 3);           // UART 0 TX BIT 8
SI_SBIT(RB80, SFR_SCON0, 2);           // UART 0 RX BIT 8
SI_SBIT(TI0, SFR_SCON0, 1);            // UART 0 TX INTERRUPT FLAG
SI_SBIT(RI0, SFR_SCON0, 0);            // UART 0 RX INTERRUPT FLAG

//  SCON1  0x98
SI_SBIT(S1MODE, SFR_SCON1, 7);         // UART 1 MODE
                                     // Bit6 unused
SI_SBIT(MCE1, SFR_SCON1, 5);           // UART 1 MCE
SI_SBIT(REN1, SFR_SCON1, 4);           // UART 1 RX ENABLE
SI_SBIT(TB81, SFR_SCON1, 3);           // UART 1 TX BIT 8
SI_SBIT(RB81, SFR_SCON1, 2);           // UART 1 RX BIT 8
SI_SBIT(TI1, SFR_SCON1, 1);            // UART 1 TX INTERRUPT FLAG
SI_SBIT(RI1, SFR_SCON1, 0);            // UART 1 RX INTERRUPT FLAG

//  IE  0xA8
SI_SBIT(EA, SFR_IE, 7);                // GLOBAL INTERRUPT ENABLE
                                     // Bit6 unused
SI_SBIT(ET2, SFR_IE, 5);               // TIMER 2 INTERRUPT ENABLE
SI_SBIT(ES0, SFR_IE, 4);               // UART0 INTERRUPT ENABLE
SI_SBIT(ET1, SFR_IE, 3);               // TIMER 1 INTERRUPT ENABLE
SI_SBIT(EX1, SFR_IE, 2);               // EXTERNAL INTERRUPT 1 ENABLE
SI_SBIT(ET0, SFR_IE, 1);               // TIMER 0 INTERRUPT ENABLE
SI_SBIT(EX0, SFR_IE, 0);               // EXTERNAL INTERRUPT 0 ENABLE

//  IP  0xB8
                                     // Bit7 unused
                                     // Bit6 unused
SI_SBIT(PT2, SFR_IP, 5);               // TIMER 2 PRIORITY
SI_SBIT(PS0, SFR_IP, 4);                // SERIAL PORT PRIORITY
SI_SBIT(PT1, SFR_IP, 3);               // TIMER 1 PRIORITY
SI_SBIT(PX1, SFR_IP, 2);               // EXTERNAL INTERRUPT 1 PRIORITY
SI_SBIT(PT0, SFR_IP, 1);               // TIMER 0 PRIORITY
SI_SBIT(PX0, SFR_IP, 0);               // EXTERNAL INTERRUPT 0 PRIORITY

// SMB0CN 0xC0
SI_SBIT(BUSY, SFR_SMB0CN, 7);          // SMBUS 0 BUSY
SI_SBIT(ENSMB, SFR_SMB0CN, 6);         // SMBUS 0 ENABLE
SI_SBIT(STA, SFR_SMB0CN, 5);           // SMBUS 0 START FLAG
SI_SBIT(STO, SFR_SMB0CN, 4);           // SMBUS 0 STOP FLAG
SI_SBIT(SI, SFR_SMB0CN, 3);            // SMBUS 0 INTERRUPT PENDING FLAG
SI_SBIT(AA, SFR_SMB0CN, 2);            // SMBUS 0 ASSERT/ACKNOWLEDGE FLAG
SI_SBIT(SMBFTE, SFR_SMB0CN, 1);        // SMBUS 0 FREE TIMER ENABLE
SI_SBIT(SMBTOE, SFR_SMB0CN, 0);        // SMBUS 0 TIMEOUT ENABLE

// CAN0STA 0xC0
SI_SBIT(BOFF, SFR_CAN0STA, 7);         // Bus Off Status
SI_SBIT(EWARN, SFR_CAN0STA, 6);        // Warning Status
SI_SBIT(EPASS, SFR_CAN0STA, 5);        // Error Passive
SI_SBIT(RXOK, SFR_CAN0STA, 4);         // Received Message Successfully
SI_SBIT(TXOK, SFR_CAN0STA, 3);         // Transmit a Message Successfully
SI_SBIT(LEC2, SFR_CAN0STA, 2);         // Last error code bit 2
SI_SBIT(LEC1, SFR_CAN0STA, 1);         // Last error code bit 1
SI_SBIT(LEC0, SFR_CAN0STA, 0);         // Last error code bit

//  TMR2CN  0xC8
SI_SBIT(TF2, SFR_TMR2CN, 7);           // TIMER 2 OVERFLOW FLAG
SI_SBIT(EXF2, SFR_TMR2CN, 6);          // TIMER 2 EXTERNAL FLAG
                                     // Bit5 unused
                                     // Bit4 unused
SI_SBIT(EXEN2, SFR_TMR2CN, 3);         // TIMER 2 EXTERNAL ENABLE FLAG
SI_SBIT(TR2, SFR_TMR2CN, 2);           // TIMER 2 ON/OFF CONTROL
SI_SBIT(CT2, SFR_TMR2CN, 1);           // TIMER 2 COUNTER SELECT
SI_SBIT(CPRL2, SFR_TMR2CN, 0);         // TIMER 2 CAPTURE SELECT

//  TMR3CN  0xC8
SI_SBIT(TF3, SFR_TMR3CN, 7);           // TIMER 3 OVERFLOW FLAG
SI_SBIT(EXF3, SFR_TMR3CN, 6);          // TIMER 3 EXTERNAL FLAG
                                     // Bit5 unused
                                     // Bit4 unused
SI_SBIT(EXEN3, SFR_TMR3CN, 3);         // TIMER 3 EXTERNAL ENABLE FLAG
SI_SBIT(TR3, SFR_TMR3CN, 2);           // TIMER 3 ON/OFF CONTROL
SI_SBIT(CT3, SFR_TMR3CN, 1);           // TIMER 3 COUNTER SELECT
SI_SBIT(CPRL3, SFR_TMR3CN, 0);         // TIMER 3 CAPTURE SELECT

//  TMR4CN  0xC8
SI_SBIT(TF4, SFR_TMR4CN, 7);           // TIMER 4 OVERFLOW FLAG
SI_SBIT(EXF4, SFR_TMR4CN, 6);          // TIMER 4 EXTERNAL FLAG
                                     // Bit5 unused
                                     // Bit4 unused
SI_SBIT(EXEN4, SFR_TMR4CN, 3);         // TIMER 4 EXTERNAL ENABLE FLAG
SI_SBIT(TR4, SFR_TMR4CN, 2);           // TIMER 4 ON/OFF CONTROL
SI_SBIT(CT4, SFR_TMR4CN, 1);           // TIMER 4 COUNTER SELECT
SI_SBIT(CPRL4, SFR_TMR4CN, 0);         // TIMER 4 CAPTURE SELECT

//  PSW 0xD0
SI_SBIT(CY, SFR_PSW, 7);               // CARRY FLAG
SI_SBIT(AC, SFR_PSW, 6);               // AUXILIARY CARRY FLAG
SI_SBIT(F0, SFR_PSW, 5);               // USER FLAG 0
SI_SBIT(RS1, SFR_PSW, 4);              // REGISTER BANK SELECT 1
SI_SBIT(RS0, SFR_PSW, 3);              // REGISTER BANK SELECT 0
SI_SBIT(OV, SFR_PSW, 2);               // OVERFLOW FLAG
SI_SBIT(F1, SFR_PSW, 1);               // USER FLAG 1
SI_SBIT(P, SFR_PSW, 0);                // ACCUMULATOR PARITY FLAG

// PCA0CN 0xD8
SI_SBIT(CF, SFR_PCA0CN, 7);            // PCA 0 COUNTER OVERFLOW FLAG
SI_SBIT(CR, SFR_PCA0CN, 6);            // PCA 0 COUNTER RUN CONTROL BIT
SI_SBIT(CCF5, SFR_PCA0CN, 5);          // PCA 0 MODULE 5 INTERRUPT FLAG
SI_SBIT(CCF4, SFR_PCA0CN, 4);          // PCA 0 MODULE 4 INTERRUPT FLAG
SI_SBIT(CCF3, SFR_PCA0CN, 3);          // PCA 0 MODULE 3 INTERRUPT FLAG
SI_SBIT(CCF2, SFR_PCA0CN, 2);          // PCA 0 MODULE 2 INTERRUPT FLAG
SI_SBIT(CCF1, SFR_PCA0CN, 1);          // PCA 0 MODULE 1 INTERRUPT FLAG
SI_SBIT(CCF0, SFR_PCA0CN, 0);          // PCA 0 MODULE 0 INTERRUPT FLAG

// ADC0CN 0xE8
SI_SBIT(AD0EN, SFR_ADC0CN, 7);         // ADC 0 ENABLE
SI_SBIT(AD0TM, SFR_ADC0CN, 6);         // ADC 0 TRACK MODE
SI_SBIT(AD0INT, SFR_ADC0CN, 5);        // ADC 0 EOC INTERRUPT FLAG
SI_SBIT(AD0BUSY, SFR_ADC0CN, 4);       // ADC 0 BUSY FLAG
SI_SBIT(AD0CM1, SFR_ADC0CN, 3);        // ADC 0 CONVERT START MODE BIT 1
SI_SBIT(AD0CM0, SFR_ADC0CN, 2);        // ADC 0 CONVERT START MODE BIT 0
SI_SBIT(AD0WINT, SFR_ADC0CN, 1);       // ADC 0 WINDOW INTERRUPT FLAG
SI_SBIT(AD0LJST, SFR_ADC0CN, 0);       // ADC 0 LEFT JUSTIFY SELECT

// ADC2CN 0xE8
SI_SBIT(AD2EN, SFR_ADC2CN, 7);         // ADC 2 ENABLE
SI_SBIT(AD2TM, SFR_ADC2CN, 6);         // ADC 2 TRACK MODE
SI_SBIT(AD2INT, SFR_ADC2CN, 5);        // ADC 2 EOC INTERRUPT FLAG
SI_SBIT(AD2BUSY, SFR_ADC2CN, 4);       // ADC 2 BUSY FLAG
SI_SBIT(AD2CM2, SFR_ADC2CN, 3);        // ADC 2 CONVERT START MODE BIT 2
SI_SBIT(AD2CM1, SFR_ADC2CN, 2);        // ADC 2 CONVERT START MODE BIT 1
SI_SBIT(AD2CM0, SFR_ADC2CN, 1);        // ADC 2 CONVERT START MODE BIT 0
SI_SBIT(AD2WINT, SFR_ADC2CN, 0);       // ADC 2 WINDOW INTERRUPT FLAG

// SPI0CN 0xF8
SI_SBIT(SPIF, SFR_SPI0CN, 7);          // SPI 0 INTERRUPT FLAG
SI_SBIT(WCOL, SFR_SPI0CN, 6);          // SPI 0 WRITE COLLISION FLAG
SI_SBIT(MODF, SFR_SPI0CN, 5);          // SPI 0 MODE FAULT FLAG
SI_SBIT(RXOVRN, SFR_SPI0CN, 4);        // SPI 0 RX OVERRUN FLAG
SI_SBIT(NSSMD1, SFR_SPI0CN, 3);       // SPI 0 SLAVE SELECT MODE BIT 1
SI_SBIT(NSSMD0, SFR_SPI0CN, 2);       // SPI 0 SLAVE SELECT MODE BIT 0
SI_SBIT(TXBMT, SFR_SPI0CN, 1);         // SPI 0 TX BUFFER EMPTY
SI_SBIT(SPIEN, SFR_SPI0CN, 0);         // SPI 0 SPI ENABLE

// CAN0CN 0xF8
SI_SBIT(CANINIT, SFR_CAN0CN, 0);       // CAN Initialization bit
SI_SBIT(CANIE, SFR_CAN0CN, 1);         // CAN Module Interrupt Enable Bit
SI_SBIT(CANSIE, SFR_CAN0CN, 2);        // CAN Status change Interrupt Enable Bit
SI_SBIT(CANEIE, SFR_CAN0CN, 3);        // CAN Module Error Interrupt Enable Bit
SI_SBIT(CANIF, SFR_CAN0CN, 4);         // CAN Module Interrupt Flag
SI_SBIT(CANDAR, SFR_CAN0CN, 5);        // CAN Disable Automatic Retransmission bit
SI_SBIT(CANCCE, SFR_CAN0CN, 6);        // CAN Configuration Change Enable bit
SI_SBIT(CANTEST, SFR_CAN0CN, 7);       // CAN Test Mode Enable bit


//-----------------------------------------------------------------------------
// SFR PAGE DEFINITIONS
//-----------------------------------------------------------------------------

#define  CONFIG_PAGE       0x0F      // SYSTEM AND PORT CONFIGURATION PAGE
#define  LEGACY_PAGE       0x00      // LEGACY SFR PAGE
#define  TIMER01_PAGE      0x00      // TIMER 0 AND TIMER 1
#define  CPT0_PAGE         0x01      // COMPARATOR 0
#define  CPT1_PAGE         0x02      // COMPARATOR 1
#define  CPT2_PAGE         0x03      // COMPARATOR 2
#define  UART0_PAGE        0x00      // UART 0
#define  UART1_PAGE        0x01      // UART 1
#define  SPI0_PAGE         0x00      // SPI 0
#define  EMI0_PAGE         0x00      // EXTERNAL MEMORY INTERFACE
#define  ADC0_PAGE         0x00      // ADC 0
#define  ADC2_PAGE         0x02      // ADC 2
#define  SMB0_PAGE         0x00      // SMBUS 0
#define  TMR2_PAGE         0x00      // TIMER 2
#define  TMR3_PAGE         0x01      // TIMER 3
#define  TMR4_PAGE         0x02      // TIMER 4
#define  DAC0_PAGE         0x00      // DAC 0
#define  DAC1_PAGE         0x01      // DAC 1
#define  PCA0_PAGE         0x00      // PCA 0
#define  CAN0_PAGE         0x01      // CAN 0


//-----------------------------------------------------------------------------
// Interrupt Priorities
//-----------------------------------------------------------------------------

#define INTERRUPT_INT0                 0  // External Interrupt 0
#define INTERRUPT_TIMER0               1  // Timer0 Overflow
#define INTERRUPT_INT1                 2  // External Interrupt 1
#define INTERRUPT_TIMER1               3  // Timer1 Overflow
#define INTERRUPT_UART0                4  // UART0 Interrupt
#define INTERRUPT_TIMER2               5  // Timer2 Overflow
#define INTERRUPT_SPI                  6  // SPI Interrupt
#define INTERRUPT_SMBUS                7  // SMBus Interrupt
#define INTERRUPT_ADC0_WINDOW          8  // ADC0 Window Comparison
#define INTERRUPT_PCA0                 9  // PCA0 Peripheral
#define INTERRUPT_COMPARATOR0         10  // Comparator0 Interrupt
#define INTERRUPT_COMPARATOR1         11  // Comparator1 Interrupt
#define INTERRUPT_COMPARATOR2         12  // Comparator2 Interrupt
#define INTERRUPT_TIMER3              14  // Timer3 Overflow
#define INTERRUPT_ADC0_EOC            15  // ADC0 End of Conversion
#define INTERRUPT_TIMER4              16  // Timer4 Overflow
#define INTERRUPT_ADC2_WINDOW         17  // ADC2 Window Comparison
#define INTERRUPT_ADC2_EOC            18  // ADC2 End of Conversion
#define INTERRUPT_CAN                 19  // CAN Interrupt
#define INTERRUPT_UART1               20  // UART1 Interrupt

//-----------------------------------------------------------------------------
// SDCC PDATA External Memory Paging Support
//-----------------------------------------------------------------------------

#if defined SDCC

SI_SFR(_XPAGE, 0xA2); // Point to the EMI0CN register

#endif

//-----------------------------------------------------------------------------
// Header File PreProcessor Directive
//-----------------------------------------------------------------------------

#endif                                 // #define C8051F040_DEFS_H

//-----------------------------------------------------------------------------
// End Of File
//-----------------------------------------------------------------------------
