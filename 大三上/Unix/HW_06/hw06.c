#include <pthread.h>
#include <stdio.h>
#include <math.h>
#include <time.h>
/***************************************
 * accumulate s= A[i]*B[i],0<=i<=10000 *
 **************************************/
//the shared address space
int acc;
unsigned int count;
pthread_mutex_t mylock;

void *
child_thread (p)
	void *p;
{
	int *key,temp;

	key = (int*) p;
	temp = (*key)*(*key);

	//printf ("Child thread: to accumulate %d*%d\n",*key,*key);

	//the critical section with mutual-exclusive synchronization
	pthread_mutex_lock (&mylock);
	{
		acc = acc + temp;
		count = count+1;
	}
	pthread_mutex_unlock (&mylock);
}//child_thread

int main ()
{
	int i;
	int arr[10001];
	pthread_t thread_id[10001];
	
	for(i = 0;i <= 10000;i++){
		arr[i] = i%10;
	}
	clock_t start,end;
	//initialize shared data
	acc = 0;
	count = 0;

	//initialize locks
	pthread_mutex_init (&mylock, NULL);

	//fork child threads
	start = clock();
	for (i=0;i<=10000;i++)
		pthread_create (&(thread_id[i]), NULL, child_thread, &(arr[i]));

	while (count<10000);
	end = clock();
	double total = end - start;
	printf ("Parent: accumulated acc = %u\n", acc);
	printf ("Spent %f sec\n",total/CLOCKS_PER_SEC);

	return 0;
}//main ()



















