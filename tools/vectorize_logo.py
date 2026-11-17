import os
import math
from PIL import Image

SRC_PATH = r"C:\Users\CodeMoly\.gemini\antigravity\brain\cefb42ed-b945-458f-8368-4b21c32c1d15\.user_uploaded\media_1794665261358_0eb1841d.png"
OUTPUT_DIR = r"c:\Users\CodeMoly\Documents\Nokshapoth\assets\images"
os.makedirs(OUTPUT_DIR, exist_ok=True)

# 1. Load and upscale image
orig_im = Image.open(SRC_PATH).convert("RGBA")
SCALE = 4
up_im = orig_im.resize((orig_im.width * SCALE, orig_im.height * SCALE), Image.Resampling.BICUBIC)
w, h = up_im.size

# Color classification thresholds
# Red in orig -> Green in new: #39681A
# Black in orig -> Black in new: #111827
# Yellow accent -> Light Green: #82BA32

def ramer_douglas_peucker(points, epsilon):
    if len(points) < 3:
        return points
    dmax = 0.0
    index = 0
    p1 = points[0]
    p2 = points[-1]
    dx = p2[0] - p1[0]
    dy = p2[1] - p1[1]
    dist_p1_p2 = math.hypot(dx, dy)
    
    for i in range(1, len(points) - 1):
        p = points[i]
        if dist_p1_p2 == 0:
            d = math.hypot(p[0] - p1[0], p[1] - p1[1])
        else:
            d = abs(dy * p[0] - dx * p[1] + p2[0] * p1[1] - p2[1] * p1[0]) / dist_p1_p2
        if d > dmax:
            index = i
            dmax = d
            
    if dmax > epsilon:
        rec1 = ramer_douglas_peucker(points[:index+1], epsilon)
        rec2 = ramer_douglas_peucker(points[index:], epsilon)
        return rec1[:-1] + rec2
    else:
        return [p1, p2]

def extract_loops(mask, w, h):
    edges = set()
    for y in range(h):
        for x in range(w):
            if mask[y][x]:
                if y == 0 or not mask[y-1][x]:
                    edges.add(((x, y), (x+1, y)))
                if y == h-1 or not mask[y+1][x]:
                    edges.add(((x+1, y+1), (x, y+1)))
                if x == w-1 or not mask[y][x+1]:
                    edges.add(((x+1, y), (x+1, y+1)))
                if x == 0 or not mask[y][x-1]:
                    edges.add(((x, y+1), (x, y)))
    
    edge_dict = {}
    for p1, p2 in edges:
        edge_dict.setdefault(p1, []).append(p2)
        
    loops = []
    visited = set()
    for start_p in list(edge_dict.keys()):
        for next_p in edge_dict[start_p]:
            if (start_p, next_p) in visited:
                continue
            loop = [start_p]
            curr = next_p
            visited.add((start_p, curr))
            while curr != start_p and curr in edge_dict:
                loop.append(curr)
                found = False
                for cand in edge_dict[curr]:
                    if (curr, cand) not in visited:
                        visited.add((curr, cand))
                        curr = cand
                        found = True
                        break
                if not found:
                    break
            if curr == start_p and len(loop) > 3:
                loops.append(loop)
    return loops

def loops_to_svg_path(loops, epsilon=1.2, offset_x=0, offset_y=0):
    path_d = []
    for loop in loops:
        simplified = ramer_douglas_peucker(loop, epsilon)
        if len(simplified) < 3:
            continue
        first = simplified[0]
        cmds = [f"M {first[0] - offset_x:.1f} {first[1] - offset_y:.1f}"]
        for pt in simplified[1:]:
            cmds.append(f"L {pt[0] - offset_x:.1f} {pt[1] - offset_y:.1f}")
        cmds.append("Z")
        path_d.append(" ".join(cmds))
    return " ".join(path_d)

# Masks for each component
icon_green_mask = [[False]*w for _ in range(h)]
icon_black_mask = [[False]*w for _ in range(h)]
icon_accent_mask = [[False]*w for _ in range(h)]

text_green_mask = [[False]*w for _ in range(h)]
text_black_mask = [[False]*w for _ in range(h)]
tagline_mask = [[False]*w for _ in range(h)]

Y_SPLIT_ICON_TEXT = 59 * SCALE
Y_SPLIT_TEXT_TAGLINE = 87 * SCALE

for y in range(h):
    for x in range(w):
        r, g, b, a = up_im.getpixel((x, y))
        if a < 70:
            continue
            
        is_red = (r > 130 and r > g * 1.35 and r > b * 1.35)
        is_black = (r < 95 and g < 95 and b < 95)
        is_yellow = (r > 160 and g > 110 and b < 90)
        
        if y < Y_SPLIT_ICON_TEXT:
            if is_yellow:
                icon_accent_mask[y][x] = True
            elif is_red:
                icon_green_mask[y][x] = True
            elif is_black:
                icon_black_mask[y][x] = True
        elif y < Y_SPLIT_TEXT_TAGLINE:
            if is_red:
                text_green_mask[y][x] = True
            elif is_black:
                text_black_mask[y][x] = True
        else:
            tagline_mask[y][x] = True

# Extract loops
icon_green_loops = extract_loops(icon_green_mask, w, h)
icon_black_loops = extract_loops(icon_black_mask, w, h)
icon_accent_loops = extract_loops(icon_accent_mask, w, h)

text_green_loops = extract_loops(text_green_mask, w, h)
text_black_loops = extract_loops(text_black_mask, w, h)
tagline_loops = extract_loops(tagline_mask, w, h)

print(f"Loops extracted: Icon(G:{len(icon_green_loops)}, B:{len(icon_black_loops)}, A:{len(icon_accent_loops)}) | Text(G:{len(text_green_loops)}, B:{len(text_black_loops)}) | Tagline:{len(tagline_loops)}")

COLOR_GREEN = "#39681A"
COLOR_BLACK = "#1A1A1A"
COLOR_ACCENT = "#82BA32"

# 1. ICON ONLY SVG
# Bounding box of icon: x around 74*SCALE to 125*SCALE, y: 3*SCALE to 59*SCALE
icon_all_loops = icon_green_loops + icon_black_loops + icon_accent_loops
all_icon_pts = [p for l in icon_all_loops for p in l]
min_ix = min(p[0] for p in all_icon_pts) - 10
max_ix = max(p[0] for p in all_icon_pts) + 10
min_iy = min(p[1] for p in all_icon_pts) - 10
max_iy = max(p[1] for p in all_icon_pts) + 10
icon_w = max_ix - min_ix
icon_h = max_iy - min_iy

icon_svg = f"""<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {icon_w} {icon_h}" width="{icon_w}" height="{icon_h}" fill="none">
  <!-- Nokshapoth Icon (Green & Black Vector) -->
  <g fill-rule="evenodd">
    <!-- Back Door & Road (Black) -->
    <path fill="{COLOR_BLACK}" d="{loops_to_svg_path(icon_black_loops, offset_x=min_ix, offset_y=min_iy)}" />
    <!-- Front Door & Path Ribbon (Green) -->
    <path fill="{COLOR_GREEN}" d="{loops_to_svg_path(icon_green_loops, offset_x=min_ix, offset_y=min_iy)}" />
    <!-- Threshold Horizon Accent (Light Green) -->
    <path fill="{COLOR_ACCENT}" d="{loops_to_svg_path(icon_accent_loops, offset_x=min_ix, offset_y=min_iy)}" />
  </g>
</svg>"""

with open(os.path.join(OUTPUT_DIR, "nokshapoth-icon.svg"), "w", encoding="utf-8") as f:
    f.write(icon_svg)

# 2. TEXT ONLY SVG (Brand name + Tagline)
text_all_loops = text_green_loops + text_black_loops + tagline_loops
all_text_pts = [p for l in text_all_loops for p in l]
min_tx = min(p[0] for p in all_text_pts) - 10
max_tx = max(p[0] for p in all_text_pts) + 10
min_ty = min(p[1] for p in all_text_pts) - 8
max_ty = max(p[1] for p in all_text_pts) + 8
text_w = max_tx - min_tx
text_h = max_ty - min_ty

text_svg = f"""<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {text_w} {text_h}" width="{text_w}" height="{text_h}" fill="none">
  <!-- Nokshapoth Brand Typography (Green & Black Vector) -->
  <g fill-rule="evenodd">
    <!-- 'নকশা' (Green) -->
    <path fill="{COLOR_GREEN}" d="{loops_to_svg_path(text_green_loops, offset_x=min_tx, offset_y=min_ty)}" />
    <!-- 'পথ' (Black) -->
    <path fill="{COLOR_BLACK}" d="{loops_to_svg_path(text_black_loops, offset_x=min_tx, offset_y=min_ty)}" />
    <!-- Tagline: '— ঘর সাজে, জীবন সাজে —' (Green) -->
    <path fill="{COLOR_GREEN}" d="{loops_to_svg_path(tagline_loops, offset_x=min_tx, offset_y=min_ty)}" />
  </g>
</svg>"""

with open(os.path.join(OUTPUT_DIR, "nokshapoth-text.svg"), "w", encoding="utf-8") as f:
    f.write(text_svg)

# 3. FULL LOGO VERTICAL SVG (Icon top, Text bottom)
all_pts = all_icon_pts + all_text_pts
min_x = min(p[0] for p in all_pts) - 15
max_x = max(p[0] for p in all_pts) + 15
min_y = min(p[1] for p in all_pts) - 10
max_y = max(p[1] for p in all_pts) + 10
full_w = max_x - min_x
full_h = max_y - min_y

full_logo_svg = f"""<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {full_w} {full_h}" width="{full_w}" height="{full_h}" fill="none">
  <!-- Nokshapoth Complete Logo (Vertical Lockup) -->
  <g fill-rule="evenodd">
    <!-- Icon Elements -->
    <path fill="{COLOR_BLACK}" d="{loops_to_svg_path(icon_black_loops, offset_x=min_x, offset_y=min_y)}" />
    <path fill="{COLOR_GREEN}" d="{loops_to_svg_path(icon_green_loops, offset_x=min_x, offset_y=min_y)}" />
    <path fill="{COLOR_ACCENT}" d="{loops_to_svg_path(icon_accent_loops, offset_x=min_x, offset_y=min_y)}" />

    <!-- Typography Elements -->
    <path fill="{COLOR_GREEN}" d="{loops_to_svg_path(text_green_loops, offset_x=min_x, offset_y=min_y)}" />
    <path fill="{COLOR_BLACK}" d="{loops_to_svg_path(text_black_loops, offset_x=min_x, offset_y=min_y)}" />
    <path fill="{COLOR_GREEN}" d="{loops_to_svg_path(tagline_loops, offset_x=min_x, offset_y=min_y)}" />
  </g>
</svg>"""

with open(os.path.join(OUTPUT_DIR, "nokshapoth-logo.svg"), "w", encoding="utf-8") as f:
    f.write(full_logo_svg)

# 4. HORIZONTAL LOCKUP LOGO (Icon on left, Text on right - perfect for navbar)
GAP = 30
h_total_w = icon_w + GAP + text_w
h_total_h = max(icon_h, text_h)
icon_y_offset = (h_total_h - icon_h) / 2
text_y_offset = (h_total_h - text_h) / 2

h_logo_svg = f"""<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {h_total_w:.1f} {h_total_h:.1f}" width="{h_total_w:.1f}" height="{h_total_h:.1f}" fill="none">
  <!-- Nokshapoth Horizontal Lockup (For Navbar & Headers) -->
  <g fill-rule="evenodd">
    <!-- Left: Icon -->
    <g transform="translate(0, {icon_y_offset:.1f})">
      <path fill="{COLOR_BLACK}" d="{loops_to_svg_path(icon_black_loops, offset_x=min_ix, offset_y=min_iy)}" />
      <path fill="{COLOR_GREEN}" d="{loops_to_svg_path(icon_green_loops, offset_x=min_ix, offset_y=min_iy)}" />
      <path fill="{COLOR_ACCENT}" d="{loops_to_svg_path(icon_accent_loops, offset_x=min_ix, offset_y=min_iy)}" />
    </g>

    <!-- Right: Typography -->
    <g transform="translate({icon_w + GAP}, {text_y_offset:.1f})">
      <path fill="{COLOR_GREEN}" d="{loops_to_svg_path(text_green_loops, offset_x=min_tx, offset_y=min_ty)}" />
      <path fill="{COLOR_BLACK}" d="{loops_to_svg_path(text_black_loops, offset_x=min_tx, offset_y=min_ty)}" />
      <path fill="{COLOR_GREEN}" d="{loops_to_svg_path(tagline_loops, offset_x=min_tx, offset_y=min_ty)}" />
    </g>
  </g>
</svg>"""

with open(os.path.join(OUTPUT_DIR, "nokshapoth-logo-horizontal.svg"), "w", encoding="utf-8") as f:
    f.write(h_logo_svg)

print("All 4 SVG files generated successfully in assets/images/!")
