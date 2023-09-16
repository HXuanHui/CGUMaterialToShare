
hw01: main.o calculate.o lib_mult.a
	gcc $^  -g -o hw01

%.o:%.c HW_01.h
	gcc -c -g -O2 $<

clean:
	rm -f *.o
	rm -f hw01
