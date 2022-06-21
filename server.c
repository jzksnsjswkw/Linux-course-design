// server.c 服务端
#include <arpa/inet.h>
#include <errno.h>
#include <netinet/in.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <sys/socket.h>
#include <sys/types.h>
#include <sys/wait.h>
#include <unistd.h>
#define SERVPORT 3333
#define BACKLOG 10

void *sendMessage(void *args)
{
    char s[50];
    int client_fd = *(int *)args;
    for (;;)
    {
        scanf("%s", s);
        send(client_fd, s, strlen(s) + 1, 0);
    }
    return 0;
}

void *recvMessage(void *args)
{
    char s[50];
    int client_fd = *(int *)args;
    for (;;)
    {
        int nbytes = recv(client_fd, s, 100, 0);
        if (nbytes <= 0)
        {
            printf("连接已断开");
            return 0;
        }
        s[nbytes] = '\0';
        printf("收到：%s\n", s);
    }
    return 0;
}

int main()
{
    int sockfd, client_fd;
    int sin_size;
    char s[50];
    pthread_t th_send;
    pthread_t th_recv;
    struct sockaddr_in my_addr;
    struct sockaddr_in remote_addr;
    sockfd = socket(AF_INET, SOCK_STREAM, 0); //建立socket --
    my_addr.sin_family = AF_INET;             // AF_INET地址族
    my_addr.sin_port = htons(SERVPORT);       //设定端口号(host -> networks)
    my_addr.sin_addr.s_addr = INADDR_ANY;     // 32位IPv4地址
    bzero(&(my_addr.sin_zero), 8);            //置前8个字节为0
    if (bind(sockfd, (struct sockaddr *)&my_addr, sizeof(struct sockaddr)) == -1)
    {
        perror("bind 出错！");
        exit(1);
    }
    if (listen(sockfd, BACKLOG) == -1) //监听socket连接，设置队列中最多拥有连接个数为10  --
    {
        perror("listen 出错！");
        exit(1);
    }
    sin_size = sizeof(struct sockaddr_in);
    for (;;) //记录sockaddr_in结构体所占字节数
    {
        if ((client_fd = accept(sockfd, (struct sockaddr *)&remote_addr, &sin_size)) == -1) // accept()缺省是阻塞函数，阻塞到有连接请求为止 --
        {
            perror("accept error");
            continue;
        }
        printf("收到一个连接来自： %s\n", inet_ntoa(remote_addr.sin_addr));
        if (!fork())
        {
            if (send(client_fd, "连接上了 \n", 26, 0) == -1) //--
                perror("send 出错！");
            // receive a piece of message from host
            int nbytes = recv(client_fd, s, 100, 0);
            s[nbytes] = '\0';
            if (s[0] == 'l' && s[1] == 'i' && s[2] == 'u' && nbytes == 3)
            {
                pthread_create(&th_send, NULL, sendMessage, (void *)&client_fd);
                pthread_create(&th_recv, NULL, recvMessage, (void *)&client_fd);
                pthread_join(th_recv, NULL);
            }
            else
            {
                // send the message "incorrect username" to host
                send(client_fd, "incorrect username\n", 26, 0);
            }
            close(client_fd);
            exit(0);
        }
    }
    close(client_fd);
}