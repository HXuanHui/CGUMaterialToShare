class myfstream{
    public:
        void Open(const char* file_name,int mode);
        void Close();
        void Read(string line,size_t len);
        void Write(string line,size_t len);
        myfstream operator<<(int line);
        myfstream operator<<(float line);
        myfstream operator<<(double line);
        myfstream operator<<(char line);
        // myfstream operator>>(myfstream line);
    private:
        int fd;
        // int intValue; 
        // float floatValue;
        // double doubleValue;
};