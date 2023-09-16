#include <sys/types.h>
#include <sys/stat.h>
#include <fcntl.h>
#include <unistd.h>
#include <errno.h>

#include <stdio.h>
#include <cstring>
#include <string.h>

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

    // int c = mkfifo( "c2s.fifo",  0644);
    // int s = mkfifo( "s2c.fifo",  0644);
    
    s2cfd = open("s2c.fifo",O_RDONLY);

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
        if (c2sfd < 0)c2sfd = open("c2s.fifo",O_WRONLY);
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
    // write(c2sfd,depo,);
}

void Search(int c2sfd,int s2cfd){
    string ID;
    write(c2sfd,"2",strlen("2")+1);
    cout<<"Enter the ID to search:";
    cin>>ID;
}

void Delete(int c2sfd,int s2cfd){
    string ID;
    write(c2sfd,"3",strlen("3")+1);
    cout<<"Enter the ID to delete:";
    cin>>ID;
}

void List(int c2sfd,int s2cfd){
    write(c2sfd,"4",strlen("4")+1);
}