#include <unistd.h>
#include <fcntl.h>
#include <string.h>
// #include <cstring>
// #include <iostream>
// using namespace std;
#include "myfstream.h"
void myfstream::Open(const char* file_name,int mode){
    int fileDscpt;
    int flag;
    if(mode == 16){ //ios::out
        fileDscpt = open (file_name,O_CREAT|O_WRONLY,0666);
    }
    else{ //ios::in
        fileDscpt = open (file_name,O_CREAT|O_RDONLY,0666);
    }
    fd = fileDscpt;
    // cout<<fd<<endl;
    // cout<<flag<<endl;
    // cout<<(int)O_RDONLY<<endl;
    // cout<<(int)O_WRONLY<<endl;
}
void myfstream::Close(){
    close(fd);
}
void myfstream::Write(string line,size_t count){//input to file
    write(fd,&line,count);
}
void myfstream::Read(string line,size_t count){//output from file
    read(fd,&line,count);
}
myfstream myfstream::operator <<(int line){
    read(fd,&line,sizeof(line));
}
myfstream myfstream::operator <<(float line){
    read(fd,&line,sizeof(line));
}
myfstream myfstream::operator <<(double line){
    read(fd,&line,sizeof(line));
}
myfstream myfstream::operator <<(char line){
    read(fd,&line,sizeof(line));
}

// myfstream myfstream::operator >>(myfstream line){
//     write(fd,&line,sizeof(myfstream));

// myfstream operator>>(myfstream &out, randomString r){
//     out << r.x << endl;
//     return out;
// }
