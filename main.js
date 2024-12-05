"use strict";

(function ($) {

    $(document).ready(function () {


        console.log("Ready");
        var currentURL = window.location.href;
        console.log("🚀 ~ currentURL:", currentURL)

        var swiper = new Swiper(".propertySwiper", {
            slidesPerView: 3,
            spaceBetween: 30,
            loop: true,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false,
            },
            breakpoints: {
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                },
                768: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                480: {
                    slidesPerView: 1,
                    spaceBetween: 10,
                },
                0: {
                    slidesPerView: 1,
                    spaceBetween: 5,
                }
            }
        });

        var updateLightbox = function () {
            var src = $('.active').attr('src');
            $('#lightbox img').attr('src', src);
        };

        // Image click event
        $('.gallery img').on('click', function () {
            $('#lightbox').css('display', 'flex');
            $(this).addClass('active');
            updateLightbox();
        });

        // Close lightbox
        $('#lightbox .close').on('click', function () {
            $('.gallery img').removeClass('active');
            $('#lightbox').hide();
        });

        // Navigate to next image
        $('#lightbox .next').on('click', function () {
            var activeImage = $('.gallery img.active');
            if (activeImage.is(':last-child')) {
                activeImage.removeClass('active');
                $('.gallery img:first-child').addClass('active');
            } else {
                activeImage.removeClass('active').next('img').addClass('active');
            }
            updateLightbox();
        });

        // Navigate to previous image
        $('#lightbox .prev').on('click', function () {
            var activeImage = $('.gallery img.active');
            if (activeImage.is(':first-child')) {
                activeImage.removeClass('active');
                $('.gallery img:last-child').addClass('active');
            } else {
                activeImage.removeClass('active').prev('img').addClass('active');
            }
            updateLightbox();
        });

        // Close lightbox when clicking outside the content
        $('#lightbox').on('click', function (e) {
            if ($(e.target).is('#lightbox')) {
                $('#lightbox .close').click();
            }
        });


        // Hide and shoew the Content
        const charLimit = 200; // Set the character limit
        const fullText = $(".accContent").text().trim();
        const truncatedText = fullText.substring(0, charLimit) + "...";

        // Initially set truncated text
        $(".accContent").text(truncatedText);

        $(".toggle-btn").click(function (e) {
            e.preventDefault();
            const details = $(".accContent");

            if (details.text() === truncatedText) {
                details.text(fullText);
                $(this).text("Hide Details");
            } else {
                details.text(truncatedText);
                $(this).text("More Details");
            }
        });


        const $calendarDiv = $('#availability-calendar');
        const events = JSON.parse($calendarDiv.attr('data-events') || '[]');

        function generateCalendar(events) {
            const today = new Date();
            let html = '';

            for (let monthOffset = 0; monthOffset < 6; monthOffset++) {
                const monthDate = new Date(today.getFullYear(), today.getMonth() + monthOffset, 1);
                const monthName = monthDate.toLocaleString('default', { month: 'long' });
                const year = monthDate.getFullYear();
                const daysInMonth = new Date(year, monthDate.getMonth() + 1, 0).getDate();

                html += `<div class="calendar-month"><h3>${monthName} ${year}</h3><table>`;
                html += '<tr>';
                ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'].forEach(day => {
                    html += `<th>${day}</th>`;
                });
                html += '</tr><tr>';

                const firstDay = new Date(year, monthDate.getMonth(), 1).getDay();
                for (let blank = 0; blank < firstDay; blank++) {
                    html += '<td class="empty"></td>';
                }

                for (let day = 1; day <= daysInMonth; day++) {
                    const currentDate = new Date(year, monthDate.getMonth(), day);

                    const isHighlighted = events.some(event => {
                        const start = new Date(event.start);
                        const end = new Date(event.end);
                        return currentDate >= start && currentDate < end;
                    });

                    html += `<td class="${isHighlighted ? 'highlight' : ''}">${day}</td>`;

                    if ((firstDay + day) % 7 === 0) {
                        html += '</tr><tr>';
                    }
                }

                html += '</tr></table></div>';
            }

            $calendarDiv.html(html);
        }

        generateCalendar(events);


        $('input[name="accommodation_types"]').on('change', function () {
            var slug = $(this).val(); // Get the selected value
            console.log(slug); // Log the selected value

            // Perform AJAX
            $.ajax({
                url: ULTIMATE.AJAX_URL,
                type: 'POST',
                data: {
                    action: 'switch_category', // Action hook
                    slug: slug,               // Data to send
                    nonce: ULTIMATE.NONCE,    // Security nonce
                },
                beforeSend: function () {
                    $('.ultimateRetreat').addClass('loading');
                }
            })
                .done(function (results) {
                    $('.ultimateRetreat').html(results);
                    $('.ultimateRetreat').removeClass('loading');
                });

        });





    });

}(jQuery));
