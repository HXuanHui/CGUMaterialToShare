#include <sys/types.h>
#include <sys/stat.h>
#include <fcntl.h>
#include <unistd.h>
#include <errno.h>

#include <stdio.h>
#include <cstring>
#include <string.h>

class node{
    friend class chain;
    public:
        node(const char* newName,const char* newID,int newDepo):name(newName),ID(newID),depo(newDepo),next(0){};
    private:
        const char *name,*ID;
        int depo;
        node* next;
};
class chain{
    public:
        chain():last(0),head(0){};
        void Insert(const char* newName,const char* newID,int newDepo);
        void Search(const chain &bank,const char* ID);
        void Delete(const chain &bank,const char* ID);
        void List(const chain &bank);
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

	c2sfd = open("c2s.fifo",O_RDONLY|O_NONBLOCK);
    s2cfd = open("s2c.fifo",O_WRONLY|O_NONBLOCK);
    while(read (c2sfd, cBuf, 2)>0){//get option
        string nline,iline;
        const char *name,*ID;
        int depo;

        if(strcmp(cBuf,"1")==0){
            // bank.Insert(name,ID,depo);
            cout<<"The option is"<<cBuf<<endl;
        }
        else if(strcmp(cBuf,"2")==0){
            // bank.Search(bank,ID);
            cout<<"The option is"<<cBuf<<endl;
        }
        else if(strcmp(cBuf,"3")==0){
            // bank.Delete(bank,ID);
            cout<<"The option is"<<cBuf<<endl;
        }
        else if(strcmp(cBuf,"4")==0){
            // bank.List(bank);
            cout<<"The option is"<<cBuf<<endl;
        }
        else{
            cout<<"The option doesn't exist."<<endl;
        }
    }
	return 0;
}//main()


void chain::Insert(const char* newName,const char* newID,int newDepo){
    node *newNode = new node(newName,newID,newDepo);
    if(last==0){
        cout<<"last==0"<<endl;
        last = newNode;
        head = newNode;
        return;
    }
    last->next = newNode; //倒數第二個指向最後一個
    cout<<head->name<<"　"<<newNode->ID<<" "<<newNode->depo<<endl;
    last = newNode;  //最後一個
    return ;
}

void chain::Search(const chain &bank,const char* ID){
    for(node *ptr = bank.head;ptr;ptr = ptr->next){
        cout<<"the ID you want to find"<<ID<<endl;
        if(strcmp(ID,ptr->ID)==0){
        cout<<"Name: "<<ptr->name<<endl;
        cout<<"ID: "<<ptr->ID<<endl;
        cout<<"Deposit: "<<ptr->depo<<endl;
        return ;
        }
    }
    cout<<"No found."<<endl;
    return;
}

void chain::Delete(const chain &bank,const char* ID){
    for(node *ptr = bank.head;ptr;ptr = ptr->next){
        if(strcmp(ID,ptr->ID)==0){
        cout<<"Name: "<<ptr->name<<endl;
        cout<<"ID: "<<ptr->ID<<endl;
        cout<<"Deposit: "<<ptr->depo<<endl;
        return ;
        }
    }
}
void chain::List(const chain &bank){
    if(bank.head == 0){
        cout<<"Empty"<<endl;
        return ;
    }
    for(node *ptr = bank.head;ptr;ptr = ptr->next){
        cout<<"Name: "<<ptr->name<<endl;
        cout<<"ID: "<<ptr->ID<<endl;
        cout<<"Deposit: "<<ptr->depo<<endl;
        return ;
    }
}
