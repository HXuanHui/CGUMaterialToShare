
#ifndef PERSONAL_RECORD_H

#define PERSONAL_RECORD_H

typedef struct return_num_s {
	int	retnum;
} return_num_t;

typedef struct msgbuf_s {
	long				mtype;
	return_num_t	prec;
} msgbuf_t;

#endif
