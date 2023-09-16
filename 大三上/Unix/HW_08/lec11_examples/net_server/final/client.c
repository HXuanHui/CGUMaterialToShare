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
#include <stdlib.h>

void trim(char* p);

int main (int argc, char* argv[] ){
	struct sockaddr_in myaddr;
	struct sockaddr_in server_addr;
	char client_buf[100];
	char server_buf[100];
	int sockfd;
	int status;

	//handle command
	char* addr = "127.0.0.1";
	int port = 942;
	if (argc == 3){
		addr = argv[1];
		port = atoi(argv[2]);
	}
	else if(argc != 1){
		printf("Errror: unknown command or incomplete address and port.\n");
	}

	//setup the server address
	server_addr.sin_family = PF_INET;
	server_addr.sin_port = port;
	server_addr.sin_addr.s_addr = inet_addr (addr);

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
		fgets(client_buf,100,stdin);
		client_buf[strlen(client_buf)-1] = '\0';

		//checking command and handling
		if(!strncmp(client_buf,"/",1)){
			if(!strncmp(client_buf,"/exit",5)){
				status = write (sockfd, client_buf, strlen(client_buf)+1);
				exit(1);	
			}
			else if(!strncmp(client_buf,"/send",5)){
				char fname[80],ACK[10],file_buf[10000];
				FILE *fp;
				int numbytes;
				strncpy(fname,client_buf+5,strlen(client_buf)-1);
				trim(fname); // trim space
				// read file
				fp = fopen(fname,"r");
				if(!fp){
					printf("The file doesn't exit or isn't readable.\n");
					continue;
				}
				write (sockfd, client_buf, strlen(client_buf)+1);
				printf("Sending file to the server...\n");
				read(sockfd,ACK,10);
				while(!feof(fp)){
					fread(file_buf, sizeof(char), sizeof(file_buf), fp);
					write(sockfd, file_buf, strlen(file_buf)+1);
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
			status = write (sockfd, client_buf, strlen(client_buf)+1);
		}
		
		
		//read a string from server
		status = read(sockfd, server_buf,100);
		//checking command form server
		if(!strncmp(server_buf,"/exit",5)){
			printf("Server has closed, Bye bye.\n");
			exit(1);
		}
		else if(!strncmp(server_buf,"/send",5)){
			printf("Server is sending file to you...\n");
			FILE *fp;
			int numbytes,option;
			char fname[80];
			strncpy(fname,server_buf+5,strlen(server_buf)-1);
			trim(fname); // trim space
			// open file
			while(access(fname,F_OK) == 0){
				printf("The file has already exist, please choose an option\n1.Create a new file.\n2.Replace the file.\n");
				scanf("%d",&option);
				if(option == 1){
					printf("New file name:");
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
			write(sockfd, "ok",strlen("ok"));
			printf("Recieving file from server.\n");	
			do{
				read(sockfd, server_buf, 100);
				numbytes = fwrite(server_buf, sizeof(char), strlen(server_buf)+1, fp);
			}while(!strncmp(server_buf,"\n",1));
			printf("Recieve successfully.\n");
			fclose(fp);
		}
		else{
			printf("SERVER> %s\n",server_buf);
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



