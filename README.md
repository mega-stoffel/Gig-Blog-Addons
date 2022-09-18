# Gig-Blog-Addons

Sometimes there's just the need to have some more functionality available. So I created this little plugin.

Currently it only hosts some shortcodes.

It's used on https://gig-blog.net

## Documentation

### Shortcodes

- gb_randomPost  
This function simply gets a random post of all existing posts.  
And then it returns a link to this post, named with the post's headline until the first comma.  
- gb_randomPage  
This function gets the number of all posts.  
Divides them with the number of posts per page from the options.  
THen gets a random number of all available pages.  
- gb_archive  
This function gets an archive of all existing posts. Limited with some exceptions.  
- gb_postCount    
This function gets the number of all posts.  
And then rounds it down to the lower hundreds.  
Can be used to show a rough number of published posts.  

## Test Environment

### Preparation

Install a working wp-env environment. For details, see here:  
https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/  
(it needs docker, as well, as far as I understand)

Install any git.

### Clone code

Go to any directory of your local file system and execute:  
```git clone https://github.com/mega-stoffel/Gig-Blog-Addons.git```  
Now go to the subdirectory it just created: `cd Gig-Blog-Addons`

### Run it

Easy as that: `wp-env start`  
I realized in some strange situations on a Win10 machine with a WSL2 I needed to execute it as root: `sudo wp-env start`

### Open browser

In any browser you could start this empty Wordpress instance, now. Just open: `localhost:8888`, you even have a second instance on port 8889.  
Your default access to the backend localhost:888[8/9]/wp-admin is - no shit: admin/password
