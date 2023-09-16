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

void trim(char* p);

int main (int argc,char* argv[])
{
	struct sockaddr_in myaddr;
	struct sockaddr_in client_addr;
	int sockfd;
	int streamfd;
	char client_buf[100];
	char server_buf[100];

	int status;
	int addr_size;

	//handle command
	char* address = "127.0.0.1";
	int port = 942;
	if (argc == 3){
		address = argv[1];
		port = atoi(argv[2]);
	}
	else if(argc != 1){
		printf("Error: unknown command or incomplete address and port.");
	}

	//setup my address
	bzero (&myaddr, sizeof(myaddr));
	myaddr.sin_family = PF_INET;
	myaddr.sin_port = port;
	myaddr.sin_addr.s_addr = inet_addr (address);

	//create a socket for the local address 127.0.0.1
	sockfd = socket (PF_INET, SOCK_STREAM, 0);
	if (bind (sockfd, (struct sockaddr *) &myaddr, sizeof(struct sockaddr_in)) == -1)	{
		printf("Error: unable to bind the socket\n");
		exit(1);
	}
	else{
		printf("Waiting for a client...\n");

	}
	//wait for a client to connect
	listen (sockfd, 10);
	addr_size = sizeof(client_addr);
	streamfd = accept (sockfd, (struct sockaddr *) &client_addr, &addr_size);
	printf("Client connected.\n");
	
	while(1){	
		//read a string from client
		status = read (streamfd, client_buf, 100);
		//checking command from client
		if(!strncmp(client_buf,"/exit",5)){
			printf("Client has left, Bye bye.\n");
			exit(1);
		}
		else if(!strncmp(client_buf,"/send",5)){
			printf("The client is sending file to you...\n");
			FILE *fp;
			int numbytes,option;
			char fname[80];
			strncpy(fname,client_buf+5,strlen(client_buf)-1);
			trim(fname); // trim space
			// open file
			while(access(fname,F_OK) == 0){
				printf("The file has already exist, please choose an option\n1.Create a new file.\n2.Replace the file.\n");
				scanf("%d",&option);
				if(option == 1){
					printf("New file name: ");
					scanf("%s",fname);
					fp = fopen(fname,"w");
					break;
				}
				else if(option == 2){
					fp = fopen(fname,"wb");
					break;
				}
				else{
					printf("The option %d doesn't exist.\n",option);
					continue;
				}
			}
			if(access(fname,F_OK) != 0){
				fp = fopen(fname,"wb");
			}
			//write file
			write(streamfd, "ok",strlen("ok")+1);
			printf("Recieving file from client.\n");
			do{
				read(streamfd, client_buf, 100);
				fwrite(client_buf, sizeof(char), strlen(client_buf)+1, fp);
			}while(!strncmp(client_buf,"\n",1));
			printf("Recieve successfully.\n");
			fclose(fp);
		}
		else{
			printf ("CLIENT> %s\n", client_buf);
		}
		
		
		while(1){
			//typing
			printf("SERVER> ");
			fgets(server_buf,100,stdin);
			server_buf[strlen(server_buf)-1] = '\0';
			//checking command and handling
			if(!strncmp(server_buf,"/",1)){
				if(!strncmp(server_buf,"/exit",5)){
					status = write(streamfd, server_buf,strlen(server_buf)+1);
					exit(1);
				}
				else if(!strncmp(server_buf,"/send",5)){
					char fname[80],ACK[10],file_buf[10000];
					FILE *fp;
					int numbytes;
					strncpy(fname,server_buf+5,strlen(server_buf)-1);
					trim(fname); // trim space
					// read file
					fp = fopen(fname,"r");
					if(!fp){
						printf("The file doesn't exit or isn't readable.\n");
						continue;
					}
					write (streamfd, server_buf, strlen(server_buf)+1);
					printf("Sending file to the client...\n");
					do{
						read(streamfd,ACK,10);
					}while(strncmp(ACK,"ok",2));
					while(!feof(fp)){
						fread(file_buf, sizeof(char), sizeof(file_buf), fp);
						write(streamfd, file_buf, strlen(file_buf)+1);
					}
					printf("Send successfully.\n");
					fclose(fp);
				}
				else{
					printf("Invalid command.\n");
					continue;
				}
			}
			else{
				//sending mesg to client
				status = write(streamfd, server_buf,strlen(server_buf)+1);
			}
			break;
		}
	}
	return 0;
}//main ()

void trim(char* p){
	int i = 0,j = 0;
	for(;;i++){
		if(strncmp(p+i," ",1) && strncmp(p+i,"\0",1)){
			p[j] = p[i];
			j++;
		}
		else if(p[i] == '\0'){
			p[j] = '\0';
			break;
		}
	}
}


















