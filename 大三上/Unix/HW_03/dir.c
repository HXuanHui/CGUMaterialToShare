/*******************************************************************************
 *
 * to list directory content
 *
 ******************************************************************************/

#include <unistd.h>

#include <sys/types.h>
#include <dirent.h>
#include <sys/stat.h>

#include <stdio.h>
#include <time.h>

#define MAX_PATH_LEN 500

int main()
{
	char cwd[MAX_PATH_LEN];
	DIR *dp;
	struct dirent *dir_ent_info;
	char ent_path_name[MAX_PATH_LEN*2];
	struct stat inode_info;
	int is_file,is_dir,is_charDevice,is_blkDevice,is_fifo,is_link,is_soket;

	getcwd (cwd, MAX_PATH_LEN);
	dp = opendir (cwd);
	while ((dir_ent_info=readdir(dp))!=NULL) {
		//get inode info of an directory entry
		sprintf (ent_path_name, "%s/%s", cwd, dir_ent_info->d_name);
		stat (ent_path_name, &inode_info);

		//check type of a directory entry
		is_file = S_ISREG (inode_info.st_mode);
		is_dir = S_ISDIR (inode_info.st_mode);
        is_charDevice = S_ISCHR(inode_info.st_mode);
        is_blkDevice = S_ISBLK(inode_info.st_mode);
        is_fifo = S_ISFIFO(inode_info.st_mode);
        is_link = S_ISLNK(inode_info.st_mode);
        is_soket = S_ISSOCK(inode_info.st_mode);

		//print-out info
		if (is_file)
			printf ("<REG>%-20s\tinode=%d\tsize=%d\tn_link=%d\tlast modified time: %s\n",
				dir_ent_info->d_name,
				(int)inode_info.st_ino,
				(int)inode_info.st_size,
				(int)inode_info.st_nlink,
				ctime(&inode_info.st_mtime)
			);
		else if (is_dir)
			printf ("<DIR>\t%-20s\tinode=%d\t<DIR>\t\tn_link=%d\tlast modified time: %s\n",
				dir_ent_info->d_name,
				(int)inode_info.st_ino,
				(int)inode_info.st_nlink,
				ctime(&inode_info.st_mtime)
			);
		else if(is_charDevice)
			printf ("<CHR>\t%-20s\tinode=%d\t<CHR>\t\tn_link=%d\tlast modified time: %s\n",
				dir_ent_info->d_name,
				(int)inode_info.st_ino,
				(int)inode_info.st_nlink,
				ctime(&inode_info.st_mtime)
			);
		else if(is_blkDevice)
			printf ("<BLK>\t%-20s\tinode=%d\t<BLK>\t\tn_link=%d\tlast modified time: %s\n",
				dir_ent_info->d_name,
				(int)inode_info.st_ino,
				(int)inode_info.st_nlink,
				ctime(&inode_info.st_mtime)
			);
		else if(is_link)
			printf ("<LINK>\t%-20s\tinode=%d\t<LINK>\t\tn_link=%d\tlast modified time: %s\n",
				dir_ent_info->d_name,
				(int)inode_info.st_ino,
				(int)inode_info.st_nlink,
				ctime(&inode_info.st_mtime)
			);
		else if(is_soket)
			printf ("<SOCK>\t%-20s\tinode=%d\t<SOCK>\t\tn_link=%d\tlast modified time: %s\n",
				dir_ent_info->d_name,
				(int)inode_info.st_ino,
				(int)inode_info.st_nlink,
				ctime(&inode_info.st_mtime)
			);
		else if(is_link)
			printf ("<LINK>\t%-20s\tinode=%d\t<LINK>\t\tn_link=%d\tlast modified time: %s\n",
				dir_ent_info->d_name,
				(int)inode_info.st_ino,
				(int)inode_info.st_nlink,
				ctime(&inode_info.st_mtime)
			);
			// struct stat sb;
			// printf("Last file modification:   %s", ctime(&sb.st_mtime));
	}

	return 0;
}//main()

















