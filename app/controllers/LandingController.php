<?php
declare(strict_types=1);

require_once CORE_PATH . '/Controller.php';

class LandingController extends Controller
{
    public function index(): void
    {
        $this->ensureSession();

        // The landing page is the unauthenticated entry point.
        // Reaching it from a protected area must end the authenticated session.
        if (!empty($_SESSION['user_id'])) {
            $this->invalidateSession(true);
        }

        $this->setNoCacheHeaders();
        require APP_PATH . '/views/home/index.php';
    }
}
