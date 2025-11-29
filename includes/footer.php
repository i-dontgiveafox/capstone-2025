<?php
$year = date('Y');
?>
<footer class="bg-[#1e1e1e] text-white py-8 border-t border-gray-800 mt-auto">
  <div class="container mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-6">
    
    <div class="flex flex-col md:flex-row items-center gap-3">
      <span class="text-2xl font-semibold tracking-wide flex items-center">
        <span class="font-light">Vermi</span><span class="font-bold">Care</span>
      </span>
      <span class="hidden md:block text-gray-600">|</span>
      <span class="text-sm text-gray-400">Smart Vermiculture Dashboard</span>
    </div>

    <nav class="flex gap-6 items-center">
      <a href="/capstone-2025/public/index.php" class="text-sm font-medium text-gray-300 hover:text-[#B6FC67] transition-colors duration-200">
        Home
      </a>
      <a href="/capstone-2025/public/view_logs.php" class="text-sm font-medium text-gray-300 hover:text-[#B6FC67] transition-colors duration-200">
        Logs
      </a>
    </nav>

    <div class="text-sm text-gray-400 text-center md:text-right">
      <div class="font-medium">&copy; <?php echo $year; ?> VermiCare</div>
      <div class="text-xs mt-1 text-gray-500">
        Built with care &ndash; <span class="italic text-[#B6FC67]/80">keep composting</span>
      </div>
    </div>

  </div>
</footer>