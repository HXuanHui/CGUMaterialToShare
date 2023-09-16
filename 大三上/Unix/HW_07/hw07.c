#include "./count.h"

#include <pthread.h>
#include <stdio.h>

#include <math.h>
#include <time.h>

#include <sys/types.h>
#include <sys/ipc.h>
#include <sys/shm.h>
#include <sys/stat.h>
#include <unistd.h>
#include <fcntl.h>

#include <sys/msg.h>
/***************************************
 * accumulate s= A[i]*B[i],0<=i<=10000 *
 **************************************/
//the shared address space
int countPro;
unsigned int acc;
pthread_mutex_t mylock;
#define PATHNAME "Unix/HW_07"
void *
child_thread (p)
	void *p;
{
	int *key,temp;

	int msgq_id,msgq_flag;
	msgbuf_t buf;
	return_num_t *ret;

	ret = &(buf.prec);
	key = (int*) p;
	temp = (*key)*(*key);
	

	//the critical section with mutual-exclusive synchronization
	pthread_mutex_lock (&mylock);
	{
		acc += temp;
		countPro = countPro+1;
	}
	pthread_mutex_unlock(&mylock);

	//send mesg
	ret->retnum = temp;
	//printf("Send mesg %d\n",ret->retnum);
	msgq_flag = IPC_CREAT | IPC_EXCL | S_IRUSR | S_IWUSR;
	msgq_id = msgget(ftok(PATHNAME,0), msgq_flag);
	msgsnd (msgq_id, &buf, sizeof(msgbuf_t) - sizeof(long),0);

}//child_thread

int main ()
{
	int i;
	//int acc;
	int count; 
	int arr[10001];
	pthread_t thread_id[10001];
	int msgq_id,msgq_flag;
	msgbuf_t buf;

	msgq_flag = IPC_CREAT | IPC_EXCL | S_IRUSR | S_IWUSR;
	msgq_id = msgget(ftok(PATHNAME,0), msgq_flag);
	
	clock_t start,end;
	//initialize shared data
	acc = 0;
	countPro = 0;
	count = 0;
	for(i = 0;i<=10000;i++){
		arr[i] = i%10;
	}

	//initialize locks
	pthread_mutex_init (&mylock, NULL);

	//fork child threads
	start = clock();
	msgrcv (msgq_id, &buf, sizeof(msgbuf_t) - sizeof(long), 0, 0);
	for (i=0;i<=10000;i++){
		pthread_create (&(thread_id[i]), NULL, child_thread, &(arr[i]));
	}
	//summerize
	while (countPro<10000||count<10000){
		//printf("rcv %d\n",buf.prec.retnum);
		acc += buf.prec.retnum;
		count++;
	}
	
	end = clock();
	double total = end - start;

	printf ("Accumulated acc = %u\n", acc);
	printf ("Spent %f sec\n",total/CLOCKS_PER_SEC);

	return 0;
}//main ()



















