<% require css('silvercommerce/checkout: client/dist/css/checkout.min.css') %>
<% require javascript('silvercommerce/checkout: client/dist/js/checkout.min.js') %>

<div class="content-container container checkout-container typography">
    <article>
        <h1>$Title</h1>
        <div class="content">$Content</div>
    </article>

    <% if $Estimate %>
        <div class="row line">
            <div class="unit size2of3 col-xs-12 col-sm-8">
                <% if $LoginForm %>
                    <h2><%t Framework.Login "Login" %></h2>
                    $LoginForm
                    <h3 class="clearfix text-center legend">
                        <%t Checkout.OR "OR" %>
                    </h3>
                <% end_if %>

                <% if $Form %>
                    <h2 class="legend">
                        <%t Checkout.PaymentDetails 'Enter Payment Details' %>
                    </h2>
                    $Form
                <% end_if %>
            </div>

            <div class="unit size1of3 col-xs-12 col-sm-4">
                <% with $Estimate %>
                    <% include SilverCommerce\Checkout\Includes\OrderSummary %>
                <% end_with %>
            </div>
        </div>
    <% end_if %>
</div>
