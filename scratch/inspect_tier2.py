import re

nav_path = r"e:\Tender_mng\tender_system\resources\views\layouts\navigation.blade.php"
with open(nav_path, "r", encoding="utf-8") as f:
    content = f.read()

# Let's inspect Tier 2 bounds
tier2_start = content.find("<!-- Bottom Tier: Luminous Clean Glass Navigation Bar with Dropdowns -->")
tier2_end = content.find("<!-- Mobile Drawer Navigation -->")

print("tier2_start:", tier2_start)
print("tier2_end:", tier2_end)
