;define control registers (with address)
XBR2			equ		0e3h
P3MDIN			equ		0afh
P2MDOUT			equ		0a6h
WDTCN			equ		0ffh
SFRPAGE			equ		084h
P3				equ		0b0h
P2				equ		0a0h

;define control words
CONFIG_PAGE		equ		0fh
LEGACY_PAGE		equ		00h

				;turn-off the watch-dog timer
				mov		WDTCN, #0deh
				mov		WDTCN, #0adh

				;setup port configuration
				mov		SFRPAGE, #CONFIG_PAGE		;flip page
				mov		XBR2, #0c0h
				mov		P3MDIN, #0ffh						;set input(Button)
				mov		P2MDOUT, #0ffh					;set output(LED)
				mov		SFRPAGE, #LEGACY_PAGE		;flip back page

				;detect button and display

WAIT: 
				mov		A,P3										;wait utill P1 is stressed and move to Acc
				jz		wait										;if Acc = 0 jump to WAIT
				jb		P3.0,Loop_1
				jb		P3.1,Loop_2
				jb		P3.2,Loop_3
				jb		P3.3,Loop_4
				
				
Loop_1:
				mov 	R3,#11111110B						;pattern 0f Loop1
Loop_11:
				mov		A,R3										;put pattern into Acc
				mov		P2, A										;set output
				lcall	DELAY										;slow down the light
				rr    A												;rotate B rightward
				mov   R3,A										;store the persent pattern into R3
				jb		P3.1,Loop_2							;other patterns to choose
				jb		P3.2,Loop_3
				jb		P3.3,Loop_4
				ljmp	Loop_11									;loop
				mov		R0,#40									;set delay time

Loop_2:
				mov 	R3,#00000011B						;pattern 0f Loop2
Loop_21:
				mov		A,R3
				mov		P2, A										;set output
				lcall	DELAY
				rl    A												;rotate A leftward
				mov   R3,A
				jb		P3.0,Loop_1
				jb		P3.2,Loop_3
				jb		P3.3,Loop_4
				ljmp	Loop_21
				mov		R0,#5

Loop_3:
				mov 	R3,#00101010B						;pattern 0f Loop3
Loop_31:
				mov		A,R3
				mov		P2, A										;set output
				lcall	DELAY
				rr    A												;rotate A rightward
				mov   R3,A
				mov		A,P3
				jb		P3.0,Loop_1
				jb		P3.1,Loop_2
				jb		P3.3,Loop_4
				ljmp	Loop_31
				mov		R0,#30
Loop_4:
				mov 	R3,#00011100B						;pattern 0f Loop4
Loop_41:
				mov		A,R3
				mov		P2, A										;set output
				lcall	DELAY
				rr    A												;rotate A rightward
				mov   R3,A
				jb		P3.0,Loop_1
				jb		P3.1,Loop_2
				jb		P3.2,Loop_3
				ljmp	Loop_41
				mov		R0,#30

DELAY:  mov		R1,#50
DELAY1:	mov		R2,#50
DELAY2:	djnz	R2,DELAY2								;decrese 1, if R2 != 0 jump to DELAY2
				djnz	R1,DELAY1								;decrese 1, if R1 != 0 jump to 
				djnz	R0,DELAY								;decrese 1, if R0 = 0 jump
				ret														;return to loop

				end