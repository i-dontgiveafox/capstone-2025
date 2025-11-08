<?php
$year = date('Y');
?>
<footer class="bg-[#1e1e1e] text-white py-6">
  <div class="container mx-auto px-6 md:px-24 flex flex-col md:flex-row items-center justify-between gap-4">
    <div class="flex items-center gap-3">
      <span class="text-xl font-semibold"><span class="font-light">Vermi</span><span class="font-bold">Care</span></span>
      <span class="text-sm text-white/70">&nbsp;&middot;&nbsp;Smart Vermiculture Dashboard</span>
    </div>

    <nav class="flex gap-4 items-center">
      <a href="/public/index.php" class="text-sm hover:text-[#B6FC67] px-3 py-1 rounded">Home</a>
      <a href="/public/esp-display.php" class="text-sm hover:text-[#B6FC67] px-3 py-1 rounded">Sensor Logs</a>
      <a href="/public/login.php" class="text-sm hover:text-[#B6FC67] px-3 py-1 rounded">Account</a>
    </nav>

    <div class="text-sm text-white/70 text-right">
      <div>&copy; <?php echo $year; ?> VermiCare</div>
      <div class="text-xs">Built with care &ndash; <span class="italic">keep composting</span></div>
    </div>
  </div>
</footer>
