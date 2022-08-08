<!-- ######################     Main Navigation   ########################## -->
<nav>
    <ol>
        <?php
        print '<li class="';
        if ($path_parts['filename'] == "index") {
            print ' activePage ';
        } 
        print '">';
        print '<a href="index.php">Home</a>';
        print '</li>';

        print '<li class="';
        if ($path_parts['filename'] == "aboutus") {
            print ' activePage ';
        }
        
        print '">';
        print '<a href="aboutus.php">About Us</a>';
        print '</li>';
       
        print '<li class="';
        if ($path_parts['filename'] == "menu") {
            print ' activePage ';
        }
        print '">';
        print '<a href="menu.php">Menu</a>';
        print '</li>';

        print '<li class="';
        if ($path_parts['filename'] == "news") {
            print ' activePage ';
        }
        print '">';
        print '<a href="news.php">News</a>';
        print '</li>';  
       
        print '<li class="';
        if ($path_parts['filename'] == "gallery") {
            print ' activePage ';
        }
        print '">';
        print '<a href="gallery.php">Gallery</a>';
        print '</li>';
        
        print '<li class="';
        if ($path_parts['filename'] == "form") {
            print ' activePage ';
        } 
        print '">';
        print '<a href="form.php">Sign up</a>';
        print '</li>';
        ?>
    </ol>
</nav>
