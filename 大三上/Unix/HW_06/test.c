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
	
void cumulate(int val){
	int temp;
	//printf ("Count loop: to accumulate %d*%d\n",arr[i],arr[i]);
	temp = val*val;
	acc += temp;
}

int main ()
{
	int i,temp;
	int arr[10001];
	
	for(i = 0;i <= 10000;i++){
		arr[i] = i%10;
	}
	clock_t start,end;

	//initialize shared data
	acc = 0;
	count = 0;

	start = clock();
	for (i=0;i<=10000;i++){
		cumulate(arr[i]);
	}
		
	end = clock();
	double total = end - start;
	printf ("Test: accumulated acc = %u\n", acc);
	printf ("Spent %f sec\n",total/CLOCKS_PER_SEC);

	return 0;
}//main ()



















