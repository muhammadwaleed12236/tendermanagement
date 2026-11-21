import re

# Read guest.blade.php
with open(r"e:\Tender_mng\tender_system\resources\views\layouts\guest.blade.php", "r", encoding="utf-8") as f:
    guest_content = f.read()

# Let's inspect CSS in guest_content
print("guest_content length:", len(guest_content))
