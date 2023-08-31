/*
 * MIT License
 *
 * Copyright (c) 2022 Mohamed EL QUCHIRI
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

define([
    'jquery'
], function($) {
    $('.social-login-container .block-content ul li a').click(function(e) {
        var provider = $(this).attr('class');
        var url = '/techyouknow_redirect/social/login/provider/' + provider;
        var popupWidth = 500;
        var popupHeight = 500;

        // Calculate the center position of the screen
        var screenWidth = window.screen.width;
        var screenHeight = window.screen.height;
        var popupLeft = (screenWidth - popupWidth) / 2;
        var popupTop = (screenHeight - popupHeight) / 2;

        // Open the popup window with the calculated position
        var popupFeatures = "menubar=no, status=no, scrollbars=no, width=" + popupWidth + ", height=" + popupHeight + ", left=" + popupLeft + ", top=" + popupTop;
        window.open(url, "", popupFeatures);
        e.preventDefault();
    });
});