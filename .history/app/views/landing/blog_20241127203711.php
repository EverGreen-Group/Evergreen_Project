<?php require APPROOT . '/views/inc/landing_header.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/blog.css">

<main class="blog-page">
    <!-- Page Header -->
    <section class="page-header" style="background-image: url('<?php echo URLROOT; ?>/img/categories/bg.jpg');">
        <div class="header-content">
            <h1>Blog</h1>
            <div class="breadcrumb">
                <a href="<?php echo URLROOT; ?>">Home</a>
                <span>Blog</span>
            </div>
        </div>
    </section>

    <div class="blog-container">
        <div class="blog-main">
            <?php if(isset($data['posts']) && !empty($data['posts'])) : ?>
                <?php foreach($data['posts'] as $post) : ?>
                    <article class="blog-post">
                        <div class="post-image">
                            <img src="<?php echo URLROOT; ?>/img/blog/<?php echo $post->image; ?>" alt="<?php echo $post->title; ?>">
                        </div>
                        <div class="post-content">
                            <div class="post-meta">
                                <span class="author">
                                    <i class='bx bx-user'></i> <?php echo $post->author; ?>
                                </span>
                                <span class="date">
                                    <i class='bx bx-calendar'></i> <?php echo date('d M Y', strtotime($post->created_at)); ?>
                                </span>
                                <span class="category">
                                    <i class='bx bx-folder'></i> <?php echo $post->category; ?>
                                </span>
                            </div>
                            <h2 class="post-title"><?php echo $post->title; ?></h2>
                            <p class="post-excerpt"><?php echo substr($post->content, 0, 200) . '...'; ?></p>
                            <a href="<?php echo URLROOT; ?>/landing/blog/<?php echo $post->id; ?>" class="read-more">
                                Read more <i class='bx bx-right-arrow-alt'></i>
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>

                <!-- Pagination -->
                <div class="pagination">
                    <?php if($data['page'] > 1) : ?>
                        <a href="<?php echo URLROOT; ?>/landing/blog?page=<?php echo $data['page']-1; ?>" class="prev">Previous</a>
                    <?php endif; ?>
                    
                    <?php for($i = 1; $i <= $data['total_pages']; $i++) : ?>
                        <a href="<?php echo URLROOT; ?>/landing/blog?page=<?php echo $i; ?>" 
                           class="<?php echo $i == $data['page'] ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>

                    <?php if($data['page'] < $data['total_pages']) : ?>
                        <a href="<?php echo URLROOT; ?>/landing/blog?page=<?php echo $data['page']+1; ?>" class="next">Next</a>
                    <?php endif; ?>
                </div>
            <?php else : ?>
                <div class="no-posts">
                    <i class='bx bx-news'></i>
                    <h2>No Blog Posts Found</h2>
                    <p>Check back later for new content.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <aside class="blog-sidebar">
            <!-- Search -->
            <div class="sidebar-widget">
                <h3>Search</h3>
                <form class="search-form" action="<?php echo URLROOT; ?>/landing/blog/search" method="GET">
                    <input type="text" name="q" placeholder="Search posts...">
                    <button type="submit"><i class='bx bx-search'></i></button>
                </form>
            </div>

            <!-- Categories -->
            <div class="sidebar-widget">
                <h3>Categories</h3>
                <ul class="categories-list">
                    <?php foreach($data['categories'] as $category) : ?>
                        <li>
                            <a href="<?php echo URLROOT; ?>/landing/blog/category/<?php echo $category->id; ?>">
                                <?php echo $category->name; ?>
                                <span>(<?php echo $category->post_count; ?>)</span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Recent Posts -->
            <div class="sidebar-widget">
                <h3>Recent Posts</h3>
                <ul class="recent-posts">
                    <?php foreach($data['recent_posts'] as $post) : ?>
                        <li>
                            <div class="post-thumb">
                                <img src="<?php echo URLROOT; ?>/img/blog/<?php echo $post->image; ?>" 
                                     alt="<?php echo $post->title; ?>">
                            </div>
                            <div class="post-info">
                                <h4>
                                    <a href="<?php echo URLROOT; ?>/landing/blog/<?php echo $post->id; ?>">
                                        <?php echo $post->title; ?>
                                    </a>
                                </h4>
                                <span class="date">
                                    <i class='bx bx-calendar'></i> 
                                    <?php echo date('d M Y', strtotime($post->created_at)); ?>
                                </span>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </aside>
    </div>
</main>

<?php require APPROOT . '/views/inc/landing_footer.php'; ?> 