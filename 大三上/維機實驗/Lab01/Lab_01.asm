ORG	0h
			mov			R2,#8			;R2 = 8, for loop
			mov			R3,#0			;R3 = 0,R3 = sum
			mov			R0,#20h		;R0 = 20h
			mov			R1,#28h		;R1 = 28h

loop_start:
			mov			A,@R0			;A=mem[20h]
			inc			R0				;R0 ++
			mov			B,@R1			;B = mem[28h]
			inc			R1				;R1 ++
			mul			AB				;A = A*B
			add			A,R3			;A = A + R3
			mov			R3,A			;R3 = A
			djnz		R2,loop_start

HERE:	sjmp		HERE
			end