#include <sys/types.h>
#include <sys/stat.h>
#include <fcntl.h>
#include <unistd.h>

#include <iostream>
#include <stdio.h>
#include <cstring>
#include <string.h>
using namespace std;

void Insert(int c2sfd,int s2cfd);
void Search(int c2sfd,int s2cfd);
void Delete(int c2sfd,int s2cfd);
void List(int c2sfd,int s2cfd);

int main(){
    int c2sfd = -1,s2cfd ;
    int option = 1;
    string nline,iline;
    const char *name,*ID;
    int depo;
    

    while(true){
        cout<<"-------------option-------------\n";
        cout<<"(1)Insert data record\n";
        cout<<"(2)Search data record\n";
        cout<<"(3)Delete data record\n";
        cout<<"(4)List all data records\n";
        cout<<"(5)close the front-end\n";
        cout<<"--------------------------------\n";
        cout<<"Select an option:";
        cin>>option;
        if (c2sfd < 0)c2sfd = open("c2s.fifo",O_WRONLY/*|O_NONBLOCK*/);
        s2cfd = open("s2c.fifo",O_RDONLY);
        switch(option){
            case 1:
                Insert(c2sfd,s2cfd);
                break;
            case 2:
                Search(c2sfd,s2cfd);
                break;
            case 3:
                Delete(c2sfd,s2cfd);
                break;
            case 4:
                List(c2sfd,s2cfd);
                break;
            case 5:
                cout<<"Bye bye."<<endl;
                exit(0);
            default:
                cout<<"The option doesn't exist."<<endl;
                break;
        }
    }
    return 0;
}

void Insert(int c2sfd,int s2cfd){
    string name,ID;
    const char *n,*i;
    char d[10],ret[100];
    int depo;

    write(c2sfd,"1",strlen("1")+1);

    cout<<"Name:";
    cin>>name;
    n = name.data();
    write(c2sfd,n,strlen(n)+1);

    cout<<"ID:";
    cin>>ID;
    i = ID.data();
    write(c2sfd,i,strlen(i)+1);

    cout<<"Deposit:";
    cin>>depo;
    snprintf(d,10,"%d",depo); 
    write(c2sfd,d,strlen(d)+1);

    read(s2cfd,ret,100);
    cout<<ret<<endl;    //success or fail to insert data
}

void Search(int c2sfd,int s2cfd){
    string ID;
    const char *i;
    char ret[100];

    write(c2sfd,"2",strlen("2")+1);
    cout<<"Enter the ID to search:";
    cin>>ID;
    i = ID.data();
    write(c2sfd,i,strlen(i)+1);

    for(int i = 0;i<3;i++){
        read (s2cfd,ret, 100);
        if(strcmp(ret,"0")==0){
            cout<<ret<<endl; 
            return;
        }
        cout<<ret<<endl; 
    }
    
}

void Delete(int c2sfd,int s2cfd){
    string ID;
    const char *i;
    char ret[100];

    write(c2sfd,"3",strlen("3")+1);
    cout<<"Enter the ID to delete:";
    cin>>ID;
    i = ID.data();
    write(c2sfd,i,strlen(i)+1);

    sleep(1);
    read(s2cfd,ret,100);
    cout<<ret<<endl;    //success or fail to delete data
}

void List(int c2sfd,int s2cfd){
    char ret[100];
    write(c2sfd,"4",strlen("4")+1);

    while(read (s2cfd,ret, 100)>0){
        if(strcmp(ret,"0")==0){
            cout<<ret<<endl; 
            return;
        }
        cout<<ret<<endl; 
    }
}