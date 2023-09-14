;define control registers (with address)
XBR2		equ		0e3h
P3MDIN			equ		0afh
P2MDOUT			equ		0a6h
WDTCN		equ		0ffh
SFRPAGE		equ		084h
P3				equ		0b0h
P2				equ		0a0h
;define control registers for timer control
TMOD		equ		089h
TCON		equ		088h
CKCON		equ		08eh
IE			equ		0a8h
TL0			equ		08ah
TH0			equ		08ch
;define control words
CONFIG_PAGE		equ		0fh
LEGACY_PAGE		equ		00h

		org		0h					
		ljmp	main

		org		0bh					;timer0 block is fixed to 0bh
		ljmp	Timer0_ISR

		org		0100h
main:
		lcall	Port_Config					;goto setup port and config
		lcall	Timer_Config				;goto setup timer
		mov		R0, #4							;the ISR entrance count
		mov		R1, #00000001B			;the LED pattern[1:3] to display
		mov		R2, #10101010B			;the LED pattern[4] to display
		mov	  R3, #00000000B	
		mov   R4, #00000000B			;register to show on LED
Loop:
		mov		P2, R4
		sjmp	Loop
Port_Config:
		;turn-off the watch-dog timer
		mov		WDTCN, #0deh
		mov		WDTCN, #0adh

		;setup port configuration
		mov		SFRPAGE, #CONFIG_PAGE
		mov		XBR2, #0c0h
		mov		P3MDIN, #0ffh
		mov		P2MDOUT, #0ffh
		mov		SFRPAGE, #LEGACY_PAGE
		ret

Timer_Config:
		mov		TMOD, #01h
		mov		TCON, #010h
		mov		CKCON, #010h
		mov		IE, #082h
		mov		TL0, #0
		mov		TH0, #0
		ret

Timer0_ISR:								;change LED pattern
		DJNZ	R0, reset_timer ;Decrement register and Jump if NOT Zero
		mov		R0, #4					;# of cycle to interrupt

		mov		A,P3						
		anl		A,	#00001111B	;if press any last four button, Acc!=0
		jz		change_ptr			;if didn't press, Acc=0, then jump
		mov		R3,	A						;store new option
change_ptr:
		mov		A,R3						;load original option
		anl		A,	#00000001B	;if P3.0==1
		jz		ptr2						;else jump ptr2
		mov		A, R1
		rl		A
		mov		R4, A
		mov		R1, A
ptr2:
		mov		A,R3						;load original option
		anl		A,	#00000010B	;if P3.1==1
		jz		ptr3						;else jump ptr3
		mov		A, R1
		rr		A
		mov		R4, A
		mov		R1, A
ptr3:
		mov		A,R3						;load original option
		anl		A,	#00001000B	;if P3.3==1
		jz		ptr4
		mov		A, R2
		xrl		A, #11111111B
		anl		A, #10101010B
		mov		R4, A
		mov		R2, A	
ptr4:
		

reset_timer:							;Timer0
		mov		TL0, #0
		mov		TH0, #0
		reti
		end
