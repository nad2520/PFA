<?php
class AdminController extends Controller
{
    private Profile $profileModel;
    private Book $bookModel;

    public function __construct(array $config)
    {
        parent::__construct($config);
        $this->profileModel = new Profile($config);
        $this->bookModel = new Book($config);
    }

    public function index(): void
    {
        $this->render('admin', [
            'pageTitle' => 'Admin Lexora',
            'totalUsers' => $this->countUsers(),
            'totalBooks' => count($this->bookModel->getAll()),
            'useAdminStyles' => true,
        ]);
    }

    private function countUsers(): int
    {
        $stmt = $this->db()->query('SELECT COUNT(*) AS total FROM profile');
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    private function db(): PDO
    {
        return Database::getInstance($this->config);
    }
}
