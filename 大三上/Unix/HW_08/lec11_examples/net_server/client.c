/*******************************************************************************
 *
 * The client side of the network server demo program
 *
 ******************************************************************************/

#include <sys/types.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <netinet/ip.h>
#include <arpa/inet.h>

#include <unistd.h>
#include <stdio.h>
#include <string.h>

int main ()
{
	struct sockaddr_in myaddr;
	struct sockaddr_in server_addr;
	char client_buf[100];
	char server_buf[100];
	int sockfd;
	int status;

	//setup the server address
	server_addr.sin_family = PF_INET;
	server_addr.sin_port = 942;
	server_addr.sin_addr.s_addr = inet_addr ("127.0.0.1");

	//connect to the server
	sockfd = socket (PF_INET, SOCK_STREAM, 0);
  	if (connect (sockfd, (struct sockaddr *) &server_addr, sizeof(struct sockaddr_in)) == -1) {
    		printf("Error: unable to connnect to the server\n");
    		return 1;
  	}
	else{
		printf("Connect successfully!Say something to the server.\n");
	}
	
	while(1){
		//typing and sending a string to the server
		printf("CLIENT> ");
		gets(client_buf);
		//scanf("%[^\n]s",client_buf);
		status = write (sockfd, client_buf, strlen(client_buf)+1);
		

		//read a string from server
		status = read(sockfd, server_buf,100);
		printf("SERVER> %s\n",server_buf);
	}
	return 0;
}//main ()



















