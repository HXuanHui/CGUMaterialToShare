/*******************************************************************************
 *
 * The server side of the network server demo program
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
#include <stdlib.h>

int main ()
{
	struct sockaddr_in myaddr;
	struct sockaddr_in client_addr;
	int sockfd;
	int streamfd;
	char client_buf[100];
	char server_buf[100];
	int port;
	int status;
	int addr_size;

	//setup my address
	bzero (&myaddr, sizeof(myaddr));
	myaddr.sin_family = PF_INET;
	myaddr.sin_port = 942;
	myaddr.sin_addr.s_addr = inet_addr ("127.0.0.1");

	//create a socket for the local address 127.0.0.1
	sockfd = socket (PF_INET, SOCK_STREAM, 0);
	if (bind (sockfd, (struct sockaddr *) &myaddr, sizeof(struct sockaddr_in)) == -1)	{
		printf("Error: unable to bind the socket");
		exit(1);
	}
	
	//wait for a client to connect
	listen (sockfd, 10);
	addr_size = sizeof (client_addr);
	streamfd = accept (sockfd, (struct sockaddr *) &client_addr, &addr_size);
	printf("Client connected.\n");
	
	while(1){	
		//read a string from client
		status = read (streamfd, client_buf, 100);
		printf ("CLIENT> %s\n", client_buf);

		//typing and sending a string to the client
		printf("SERVER> ");
		gets(server_buf);
		//scanf("%[^\n]s", server_buf);
		status = write(streamfd, server_buf,strlen(server_buf)+1);
	}

	return 0;
}//main ()



















