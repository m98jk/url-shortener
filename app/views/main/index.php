<?php require APPROOT . '/views/inc/new_header.php'; ?>

<!-- link input and shorten button -->
<div id="content-div">
    <div id="input-div">
        <input
            type="text"
            class="text-field content-row"
            placeholder="Enter URL here . . ."
            id="input-field"
            required />
        <button onclick="shortenURL(event,'<?php echo URLROOT?>/short')" type="button" class="content-row button">
            Shorten URL
        </button>
        <button type="button" id="clear-btn" class="content-row button">
            Clear
        </button>
    </div>
    <!-- Output and copy -->
    <div id="output-div">
        <div class="content-row" id="new-url-label">Your short URL:</div>
        <div id="new-url" class="content-row"></div>
        <a href="" target="_blank" id="shortenedURL"></a>
        <button onclick="copyToClipboard()" class="content-row button">Copy</button>
    </div>
    <div class="" id="error-div">
        <p class="content-row" id="error-text"></p>
    </div>
</div>
<?php require APPROOT . '/views/inc/new_footer.php'; ?>
