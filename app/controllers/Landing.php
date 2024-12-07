<?php
class Landing extends Controller {
    private $productModel;
    private $categoryModel;

    public function __construct() {
        // Initialize both models
        $this->productModel = $this->model('M_Product');
        $this->categoryModel = $this->model('M_Category');
    }

    public function index() {
        // Get both featured products and categories
        $featuredProducts = $this->productModel->getFeaturedProducts();
        $featuredCategories = $this->categoryModel->getFeaturedCategories();
        
        $data = [
            'featured_products' => $featuredProducts,
            'featured_categories' => $featuredCategories
        ];

        $this->view('landing/index', $data);
    }

    public function contact() {
        $data = [
            'title' => 'Contact Us',
            'description' => 'Get in touch with Evergreen Tea Factory'
        ];

        $this->view('landing/contact', $data);
    }

    public function submitContact() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'subject' => trim($_POST['subject']),
                'message' => trim($_POST['message']),
                'name_err' => '',
                'email_err' => '',
                'message_err' => ''
            ];

            // Validate Email
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            }

            // Validate Name
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter name';
            }

            // Validate Message
            if (empty($data['message'])) {
                $data['message_err'] = 'Please enter message';
            }

            // Make sure no errors
            if (empty($data['email_err']) && empty($data['name_err']) && empty($data['message_err'])) {
                // Validated
                $contactModel = $this->model('M_Contact');
                if ($contactModel->saveMessage($data)) {
                    flash('contact_message', 'Message sent successfully');
                    redirect('landing/contact');
                } else {
                    flash('contact_message', 'Something went wrong', 'alert alert-danger');
                    redirect('landing/contact');
                }
            } else {
                // Load view with errors
                $this->view('landing/contact', $data);
            }
        } else {
            redirect('landing/contact');
        }
    }

    public function blog($page = 1) {
        $blogModel = $this->model('M_Blog');
        
        // Pagination settings
        $postsPerPage = 6;
        $totalPosts = $blogModel->getTotalPosts();
        $totalPages = ceil($totalPosts / $postsPerPage);
        
        // Validate page number
        $page = (int)$page;
        if ($page < 1) $page = 1;
        if ($page > $totalPages) $page = $totalPages;
        
        $posts = $blogModel->getPosts($page, $postsPerPage);
        $categories = $blogModel->getCategories();
        $recentPosts = $blogModel->getRecentPosts(5);
        
        // Add tags for sidebar
        $popularTags = $blogModel->getPopularTags(10);
        
        $data = [
            'title' => 'Blog - Evergreen Tea Factory',
            'description' => 'Read our latest articles about tea and its benefits',
            'posts' => $posts,
            'page' => $page,
            'total_pages' => $totalPages,
            'categories' => $categories,
            'recent_posts' => $recentPosts,
            'popular_tags' => $popularTags
        ];
        
        $this->view('landing/blog', $data);
    }

    public function blogPost($id) {
        $blogModel = $this->model('M_Blog');
        $post = $blogModel->getPostById($id);
        
        if (!$post) {
            redirect('landing/blog');
        }
        
        $relatedPosts = $blogModel->getRelatedPosts($post->category_id, $id, 3);
        
        // Get post tags
        $postTags = $blogModel->getPostTags($id);
        
        $data = [
            'title' => $post->title . ' - Evergreen Tea Factory',
            'post' => $post,
            'related_posts' => $relatedPosts,
            'tags' => $postTags
        ];
        
        $this->view('landing/blog_post', $data);
    }

    public function blogCategory($categoryId, $page = 1) {
        $blogModel = $this->model('M_Blog');
        $category = $blogModel->getCategoryById($categoryId);
        
        if (!$category) {
            redirect('landing/blog');
        }
        
        // Pagination for category
        $postsPerPage = 6;
        $totalPosts = $blogModel->getTotalPostsByCategory($categoryId);
        $totalPages = ceil($totalPosts / $postsPerPage);
        
        // Validate page number
        $page = (int)$page;
        if ($page < 1) $page = 1;
        if ($page > $totalPages) $page = $totalPages;
        
        // Get posts for current category and page
        $posts = $blogModel->getPostsByCategory($categoryId, $page, $postsPerPage);
        
        $data = [
            'title' => $category->name . ' - Blog',
            'category' => $category,
            'posts' => $posts,
            'page' => $page,
            'total_pages' => $totalPages
        ];
        
        $this->view('landing/blog_category', $data);
    }

    public function blogSearch() {
        if (!isset($_GET['q'])) {
            redirect('landing/blog');
        }
        
        $searchTerm = trim($_GET['q']);
        $blogModel = $this->model('M_Blog');
        $searchResults = $blogModel->searchPosts($searchTerm);
        
        $data = [
            'title' => 'Search Results for "' . htmlspecialchars($searchTerm) . '"',
            'search_term' => $searchTerm,
            'posts' => $searchResults
        ];
        
        $this->view('landing/blog_search', $data);
    }

    public function about() {
        $data = [
            'title' => 'About Us',
            'description' => 'Learn more about Evergreen and our mission'
        ];
        
        $this->view('landing/about', $data);
    }

    public function blogTag($tagSlug, $page = 1) {
        $blogModel = $this->model('M_Blog');
        $tag = $blogModel->getTagBySlug($tagSlug);
        
        if (!$tag) {
            redirect('landing/blog');
        }
        
        // Pagination for tag
        $postsPerPage = 6;
        $totalPosts = $blogModel->getTotalPostsByTag($tag->id);
        $totalPages = ceil($totalPosts / $postsPerPage);
        
        // Validate page number
        $page = (int)$page;
        if ($page < 1) $page = 1;
        if ($page > $totalPages) $page = $totalPages;
        
        // Get posts for current tag and page
        $posts = $blogModel->getPostsByTag($tag->id, $page, $postsPerPage);
        
        $data = [
            'title' => 'Posts tagged "' . htmlspecialchars($tag->name) . '"',
            'tag' => $tag,
            'posts' => $posts,
            'page' => $page,
            'total_pages' => $totalPages
        ];
        
        $this->view('landing/blog_tag', $data);
    }
}