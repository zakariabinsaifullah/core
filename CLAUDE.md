# Core Theme — Section Design Rules

Role: Read figma design, then make the exact design in WordPress (Use Figma MCP server and Verify each design using claude browser extension)
Follow these rules whenever you design or build a section, pattern, template or page for this theme.

## 1. Container: Kadence Row Layout

-   Wrap every section in a **Kadence Row Layout** block (`kadence/rowlayout`).
-   Don't use `core/group`, `core/cover` or `core/columns` as the outer container of a section.
-   **Global rule:** don't give blocks custom names (no `"metadata":{"name":...}`). Leave every block with its default name in the List View; the user renames blocks themselves if needed.

## 2. Block priority

Pick blocks in this order, and move down a level only when the level above can't do the job:

1. **Core WordPress blocks.** These are strongly preferred for all content: headings, paragraphs, buttons, images, lists, query loops and so on.
2. **Custom blocks in the Core inserter category** (`core-theme/*`):
    - Icon
    - Carousel (with Slide)
    - Image Accordion (with Image Accordion Item)
    - Story Cards (with Story Card)
    - Ticker Gallery
    - Social Share
3. Always use Core Theme **Icon** block where icon is used, always use SVG code and saved them under **My Icons** for future use.
4. **Other Kadence blocks.** Use these only when neither core blocks nor the theme's custom blocks can do the job.

## 3. Row Layout spacing

-   A **standalone** Row Layout (a top-level section, not nested inside another Row Layout) uses:
    -   **100px** padding top and bottom
    -   **15px** padding left and right
-   In block markup that's `"padding":[100,15,100,15]`. The order is top, right, bottom, left.
-   on tablet use 70px top bottom padding (mobile will be auto like tablet)
-   **Global rule:** every top-level Row Layout has its top and bottom margin set to **0** (`"margin":[0,"",0,""],"marginUnit":"px"`). This applies everywhere: pages, posts, patterns, templates and template parts. Otherwise the theme's global block gap (1.2rem) adds about 19px between sections, on top of the padding.
-   Row Layouts nested inside another Row Layout don't get this default. Space them as the design needs.
-   but in case of same background based sections like two sections, both have same background, then use **Inner Space** 100px in total (50px-50px) while on tablet it will be (35px-35px)
-   The space between a section title (heading block) and the columns/content below it is always **50px** on every device (set it as the heading's bottom margin).

## 3a. Card columns (nested Row Layout)

-   Build card grids as a nested Row Layout (`kadence/rowlayout`) with one **Section** (`kadence/column`) per card.
-   Columns gap is always **24px**, both between columns and between rows: `"columnGutter":"custom","customGutter":[24,24,24],"collapseGutter":"custom","customRowGutter":[24,24,24]`.
-   On tablet and mobile, cards are always **1 column**: `"tabletLayout":"row","mobileLayout":"row"`.
-   For image cards, don't use `core/cover`. Use the Section's own **Background Image + Overlay** settings (`backgroundImg`, `overlay` with a `core-theme-*` colour, `overlayOpacity`).
-   Cards in the same row are always **equal height**. If the design gives a card height, set it as the Section's min height. Kadence adds padding on top of min height, so enter the design height minus the top and bottom padding (for example, 443px card − 48px padding = `"height":[395]`).
-   Card content is vertically aligned to the **bottom** (`"verticalAlignment":"bottom"`).
-   Card inner padding is an equal **24px** on all sides (`"padding":[24,24,24,24]`).
-   The gap between a card's title and its description is always **16px**. Set it with the Section's **Vertical Gap** setting (`"rowGapVariable":["custom","",""],"rowGap":[16,"",""],"rowGapUnit":"px"`), not with block margins.
-   The heading and paragraph inside a card have **0 margin and 0 padding** (`"spacing":{"margin":{"top":"0","bottom":"0"},"padding":{"top":"0","right":"0","bottom":"0","left":"0"}}`), so the Section's gap controls all spacing.

## 3b. Content columns (nested Row Layout)

-   For content layouts that aren't card grids, such as text beside an image, the column gap is **70px**: `"columnGutter":"custom","customGutter":[70,70,70]`.
-   When the columns stack on tablet and mobile, the row gap is **50px**: `"collapseGutter":"custom","customRowGutter":[50,50,50]`.
-   Card grids keep the 24px gap from section 3a.

## 4. Style with existing block settings

Style blocks through the options they already have, in this order:

1. **Block settings in the editor sidebar:** typography, colour, spacing, border, layout.
2. **The theme's presets from `theme.json`.** Never hard-code values that a preset already covers.
    - **Colours:** `core-theme-*`. Tiber is the primary, Mantis the accent, and Neutral Dark the text colour.
    - **Font sizes:** `core-theme-display`, `core-theme-heading-one` … `core-theme-heading-six`, then `-extra-large`, `-large`, `-medium` and `-small`.
    - **Fonts:** Titling Gothic FB Wide for headings, Instrument Sans for everything else.
    - **Spacing:** the `core-theme-*` spacing presets.
3. **Registered block styles**, for example the button styles Alternative, Outline and Link.
4. **The theme's editor extensions:**
    - Highlight
    - Annotation
    - Hover Color
    - Iconic Button
    - Read More Button
    - Text Max Width
    - Group Full Height, Group Overlay Background and Group Global Hover
    - Kadence Row Divider

## 5. No custom CSS or JS without permission

-   **Never** add custom CSS or JavaScript without asking the user first and getting a clear yes. This covers theme files, block "Additional CSS", inline `style` attributes that go beyond block settings, `theme.json` `css` fields, and new scripts.
-   If a design can't be built with the rules above, stop. Explain what's missing and propose the CSS/JS change, then wait for approval.

## 6. Images, Icons, Content

-   use exact text used in figma design
-   for each page, you will find its corresponding images at another page of its right side, take them from there, Like: Assets • Homepage
-   for Icons, always use SVG codes
-   **Uploading images (global rule):** the site uses **BinsOptimizer**, which converts images to WebP and compresses them **in the browser**, before the upload reaches the server. It hooks the block editor's `mediaUpload` setting. So:
    -   **Never** upload images with `studio wp media import`, the REST API directly, or by copying files into `uploads/`. Those skip BinsOptimizer and leave JPEG/PNG files unconverted.
    -   Upload through the block editor in the browser (Claude in Chrome), using the wrapped `mediaUpload` function:
        1. Put the source file in a temporary folder under `wp-content/uploads/` (e.g. `claude-upload-tmp/`) so the browser can fetch it.
        2. Open any post or page in the editor and wait until `wp.data.select('core/block-editor').getSettings().mediaUpload.__biioWrapped` is `true`.
        3. Fetch the file as a blob, wrap it in a `File` with the right name and type, and call `mediaUpload({ filesList: [file], allowedTypes: ['image'], onFileChange, onError })`. Use the returned `id` and `url` (they end in `.webp`) in the blocks.
        4. Delete the temporary folder afterwards.
    -   Check the result: the attachment's file should be `.webp` and it should have `_biio_savings` / `_biio_optimized_at` meta.
-   Icon (SVG) size is always **24px** on every device. Set only the desktop size (`"sizes":{"Desktop":24},"iconSize":24`); tablet and mobile inherit it.
-   **Icon + title gap (global rule):** when an Icon block shows a title next to the icon, the gap between them is always **16px** (`"listGap":"16px"`). If you set it by script, also set `blockStyle["--list-gap"]` to `"16px"`: the block only recalculates `blockStyle` when it renders in the editor, so changing `listGap` alone can leave the old gap in the saved markup.
-   When an icon sits in a box (border or background), the inner padding is **15px** on all sides (`"spacing":{"padding":{"top":"15px","right":"15px","bottom":"15px","left":"15px"}}`). Don't add padding to icons without a box.

## 7. Color, Typography

-   always use Core theme color and typography
-   never use custom color and typography without permission
-   never use letter spacing without permisson
