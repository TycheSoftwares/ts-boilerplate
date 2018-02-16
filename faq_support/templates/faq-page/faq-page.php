<?php
/**
 * FAQ & Support page
 */
?>
<style>
    .faq-ts-accordion {
        background-color: #ccc;
        color: #444;
        cursor: pointer;
        padding: 18px;
        width: 100%;
        border: none;
        text-align: left;
        outline: none;
        font-size: 15px;
        transition: 0.4s;
        margin-bottom: 5px;
    }

    .active, .faq-ts-accordion:hover {
        background-color: #ccc; 
    }


    .faq-ts-accordion:after {
        content: '\002B';
        color: #777;
        font-weight: bold;
        float: right;
        margin-left: 5px;
    }

    .active:after {
        content: "\2212";
    }

    .panel {
        padding: 0 18px;
        display: none;
        background-color: light-grey;
        overflow: hidden;
    }

    .main-panel {
        width: 650px !important;
    }

    .support-panel {
        padding: 5px;
    }
</style>

<div class="main-panel">
    <h3>Frequently Asked Question for Order Delivery Date Pro for WooCommerce Plugin</h3>
    <button class="faq-ts-accordion">1. I need some lead preparation time before I can make a delivery. Can I set a minimum delivery period on my WooCommerce store?</span></button>
    <div class="panel">
        <p>Yes, you can set a minimum delivery period in hours, which will be taken into consideration before showing the earliest available delivery date or time slot to your customers. This can be done under the <strong>“Minimum Delivery time (in hours)”</strong> field under the General Settings -> Date Settings tab in the Order Delivery Date on the admin side. Minutes will be accepted in the decimal format like for 30 Minutes you can use 0.50.</p>
    </div>

    <button class="faq-ts-accordion">2. The working days of my company are different than the working days of my shipping company. Can I add them differently?</button>
    <div class="panel">
        <p>Yes, you can set your company’s working days and shipping company’s working days differently. You can set up this under Shipping Days section under General Settings -> Date Settings tab in the Order Delivery Date on the admin side.</p>
    </div>

    <button class="faq-ts-accordion">3. Can I provide same day deliveries to my customers?</button>
    <div class="panel">
        <p>Yes. The Same day delivery feature enables you to get the deliveries for the same date. This is available in the Time Settings tab in the Order Delivery Date on the admin side. The current date would be available until the cut-off time set for the same date delivery is passed. Once the time is passed the date will be disabled and will have the label “Cut-Off time over”. Similarly, the Next day delivery feature works where the next day would be available for delivery.</p>
    </div>

    <button class="faq-ts-accordion">4. Can I charge my customers if they want deliveries on certain days?</button>
    <div class="panel">
        <p>Yes, you can charge your customers by adding additional charges to the delivery days/dates as well as time slots from the plugin. Charges for the days can be added under Weekday Settings tab. For time slot, you can add charges while creating time slots.</p>
    </div>

    <button class="faq-ts-accordion">5. Can the delivery date be changed for already placed orders?</button>
    <div class="panel">
        <p>Yes, the delivery date and time can be changed by the administrator as well as by the customers. The administrator can change it on WooCommerce -> Edit order page in the admin. And the customer can change it on the My Account page. To allow customers to edit the date, you need to enable <strong>“Allow Customers to edit Delivery Date & Time”</strong> checkbox under General Settings -> Additional Settings tab on the admin side.</p>
    </div>

    <button class="faq-ts-accordion">6. Can I limit the number of deliveries per day?</button>
    <div class="panel">
        <p>Yes, you can limit the number of deliveries per day. You need to set the number of deliveries in the “Maximum Order Deliveries per day (based on per order)” field under General Settings -> Date Settings tab in the Order Delivery Date on the admin side.</p>
    </div>

    <button class="faq-ts-accordion">7. Can I add different delivery schedules for different delivery zones?</button>
    <div class="panel">
        <p>Yes, you can add different delivery schedules for different shipping methods added for the default WooCommerce shipping zones.<br><br>Apart from shipping methods, you can also add different schedules for different product categories and default WooCommerce shipping classes.</p>
    </div>

    <button class="faq-ts-accordion">8. Can I export the deliveries to another calendar for easy access?</button>
    <div class="panel">
        <p>Yes, you can export your deliveries to the google calendar directly or manually by downloading ICS files. This can be done under Google Calendar Sync tab.</p>
    </div>

    <button class="faq-ts-accordion">9. Can I limit the number of deliveries per day?</button>
    <div class="panel">
        <p>Yes, you can limit the number of deliveries per day. You need to set the number of deliveries in the “Maximum Order Deliveries per day (based on per order)” field under General Settings -> Date Settings tab in the Order Delivery Date on the admin side.</p>
    </div>

    <button class="faq-ts-accordion">10. I don't want some time slots for particular of the dates or weekdays. Can I disable them?</button>
    <div class="panel">
        <p>Yes, disable time slots for certain days or dates. You can add the time slots which you want to disable under General Settings -> Time Slot -> Block a Time slots link. The time slot will not be shown on the checkout page for that particular day or date.</p>
    </div>
</div>

<div class="support-panel">
    <p style="font-size: 19px">
        If your queries are not answered here, you can contact our support team by posting an issue on our <a href="https://www.tychesoftwares.com/forums/forum/order-delivery-date-pro-for-woocommerce/" target="_blank">support forum</a>.
    </p>
    <p style="font-size: 19px">
        Or you can also send an email directly to <strong>support@tychesoftwares.com</strong> for some additional requirements. 
    </p>
</div>
<script>
var acc = document.getElementsByClassName( "faq-ts-accordion" );
var i;

for (i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click", function() {
        this.classList.toggle("active");
        var panel = this.nextElementSibling;
        if( panel.style.display === "block" ) {
            panel.style.display = "none";
        } else {
            panel.style.display = "block";
        }
    });
}
</script>