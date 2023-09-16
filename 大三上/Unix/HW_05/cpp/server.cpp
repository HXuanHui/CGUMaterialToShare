#include <sys/types.h>
#include <sys/stat.h>
#include <fcntl.h>
#include <unistd.h>

#include <iostream>
#include <stdio.h>
#include <string.h>
#include <cstring>
using namespace std;

class node{
    friend class chain;
    public:
        node(string newName,string newID,int newDepo):name(newName),ID(newID),depo(newDepo),next(0){};
    private:
        string name,ID;
        int depo;
        node* next;
};
class chain{
    friend void exit_handler ();
    public:
        chain():last(0),head(0){};
        void Insert(const chain &bank,string newName,string newID,int newDepo,int fd);
        void Search(const chain &bank,string ID,int fd);
        void Delete(const chain &bank,string ID,int fd);
        void List(const chain &bank,int fd);
    private:
        node* head;
        node* last;
};
void
exit_handler ()
{
	FILE *fp;

	fp = fopen ("autosave.out", "w");
	fprintf (fp, "I will survive!\n");
	fclose (fp);
}//exit_handler

int main()
{
	int s2cfd,c2sfd;
	char cBuf[100];
    chain bank;
    atexit (exit_handler);

	c2sfd = open("c2s.fifo",O_RDONLY);
    s2cfd = open("s2c.fifo",O_WRONLY|O_NONBLOCK);
    cout<<"Server open."<<endl;
    while(read (c2sfd, cBuf, 2)>0){//get option
        string name,ID;
        int depo;

        if(strcmp(cBuf,"1")==0){
            read (c2sfd,cBuf, 100); // name
            name = cBuf;
            cout<<"get new name!"<<endl;
            read (c2sfd,cBuf, 100); // ID
            ID = cBuf;
            cout<<"get new ID!"<<endl;
            read (c2sfd,cBuf, 100); // depo
            depo = stoi(cBuf);
            cout<<"get new deposit!"<<endl;
            bank.Insert(bank,name,ID,depo,s2cfd);
        }
        else if(strcmp(cBuf,"2")==0){
            read (c2sfd,cBuf, 100); // ID
            ID = cBuf;
            cout<<"get ID!"<<endl;
            bank.Search(bank,ID,s2cfd);
        }
        else if(strcmp(cBuf,"3")==0){
            read (c2sfd,cBuf, 100); // ID
            ID = cBuf;
            cout<<"get ID!"<<endl;
            bank.Delete(bank,ID,s2cfd);
        }
        else if(strcmp(cBuf,"4")==0){
            bank.List(bank,s2cfd);
        }
        else{
            cout<<"The option doesn't exist."<<endl;
        }
    }
	return 0;
}//main()


void chain::Insert(const chain &bank,string newName,string newID,int newDepo,int fd){
    if(last==0){
        node *newNode = new node(newName,newID,newDepo);
        last = newNode;
        head = newNode;
    }
    else{
        const char *cID = newID.c_str();
        for (node *ptr = bank.head; ptr; ptr = ptr->next) {
            const char *pID = ptr->ID.c_str();
            if (strcmp(cID, pID) == 0) {
                write(fd,"Duplicate ID. Insert failed.",strlen("Duplicate ID. Insert failed.")+1);
                return;
            }
        }
        node *newNode = new node(newName, newID, newDepo);
        last->next = newNode; //倒數第二個指向最後一個
        last = newNode;       //最後一個
    }
    write(fd,"Insert success",strlen("Insert success")+1);
    cout<<"New insertion!!"<<endl;
    return ;
}

void chain::Search(const chain &bank,string ID,int fd){
    cout<<"Searching..."<<endl;
    const char *cID = ID.c_str();
    char sBuf[100];

    for (node *ptr = bank.head; ptr; ptr = ptr->next) {
        const char *pID = ptr->ID.c_str();
        if (strcmp(cID, pID) == 0) {
            cout<<"Search: Found."<<endl;
            snprintf(sBuf,100,"Name: %s",ptr->name.c_str());
            write(fd,sBuf,strlen(sBuf)+1);

            snprintf(sBuf,100,"ID: %s",ptr->ID.c_str());
            write(fd,sBuf,strlen(sBuf)+1);
            
            sleep(1);
            snprintf(sBuf,100,"Deposit: %d",ptr->depo);
            write(fd,sBuf,strlen(sBuf)+1);

            write(fd,"0",strlen("0")+1);
            return;
        }
    }
    write(fd,"No found.",strlen("No found.")+1);
    cout << "Search: No found." << endl;
    write(fd,"0",strlen("0")+1);
    return;
}

void chain::Delete(const chain &bank,string ID,int fd){
    const char *cID = ID.c_str();
    const char *hID = head->ID.c_str();
    node *ptr = head,*follow;
    char sBuf[100];
    if (head == 0){ //empty
        write(fd,"No found.",strlen("No found.")+1);
        cout << "Search: No found." << endl;
        return;
    }
    if(strcmp(cID, hID) == 0){ //target is head
        head = head->next;
        delete ptr;
        write(fd,"Delete success",strlen("Delete success")+1);
        cout<<"Something delete."<<endl;
        return;
    }
    for (ptr = bank.head->next,follow = ptr; ptr->next; ptr = ptr->next) {
        const char *pID = ptr->ID.c_str();
        if (strcmp(cID, pID) == 0) {
            follow -> next = ptr -> next;
            delete ptr;
            write(fd,"Delete success",strlen("Delete success")+1);
            cout<<"Something delete."<<endl;
            return;
        }
    }
    write(fd,"No found.",strlen("No found.")+1);
    cout << "Delete: No found." << endl;
    return;
}
void chain::List(const chain &bank,int fd){
    if(bank.head == 0){
        write(fd,"Empty.",strlen("Empty.")+1);
        cout<<"List: Empty"<<endl;
        write(fd,"0",strlen("0")+1);
        return ;
    }
    char sBuf[100];
    cout<<"Listing..."<<endl;
    for(node *ptr = bank.head;ptr;ptr = ptr->next){
        write(fd,"--------------------------------",strlen("--------------------------------")+1);
        snprintf(sBuf,100,"Name: %s",ptr->name.c_str());
        write(fd,sBuf,strlen(sBuf)+1);
        snprintf(sBuf,100,"ID: %s",ptr->ID.c_str());
        write(fd,sBuf,strlen(sBuf)+1);
        snprintf(sBuf,100,"Deposit: %d",ptr->depo);
        write(fd,sBuf,strlen(sBuf)+1);
        
    }
    write(fd,"0",strlen("0")+1);
    cout<< "that's all."<<endl;
    return ;
}
