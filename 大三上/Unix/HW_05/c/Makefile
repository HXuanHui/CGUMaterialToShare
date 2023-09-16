TARGETS = client.exe server.exe

all: $(TARGETS)
	mkfifo c2s.fifo
	mkfifo s2c.fifo
	
%.exe: %.cc
	gcc -o $@ $^

clean:
	rm -f *.exe
	rm -f *.fifo
	rm -f autosave.out



















