<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'key' => 'registration_confirmation',
                'name' => 'Registration Confirmation',
                'subject' => 'Welcome to {app_name}!',
                'body' => '<p>Hi <strong>{customer_name}</strong>,</p>
<p>Thank you for registering at <strong>{app_name}</strong>. We\'re excited to have you as a member!</p>
<p>You can now log in and start shopping:</p>
<p><a href="{login_url}" class="btn">Login to Your Account</a></p>
<p>If you have any questions, feel free to reply to this email.</p>
<p>Happy shopping!<br><strong>{app_name} Team</strong></p>',
                'variables' => ['customer_name', 'app_name', 'app_url', 'login_url'],
            ],
            [
                'key' => 'order_placed',
                'name' => 'Order Placed (Customer)',
                'subject' => 'Order Confirmation #{order_number}',
                'body' => '<p>Hi <strong>{customer_name}</strong>,</p>
<p>Thank you for your order! We\'ve received it and it\'s being processed.</p>
<h3>Order Summary</h3>
<table>
  <tr><th>Order Number</th><td><strong>#{order_number}</strong></td></tr>
  <tr><th>Order Date</th><td>{order_date}</td></tr>
  <tr><th>Order Total</th><td><strong>{order_total}</strong></td></tr>
  <tr><th>Status</th><td>{order_status}</td></tr>
</table>
<p>We will notify you once your order has been shipped.</p>
<p>Thank you for shopping with us!<br><strong>{app_name} Team</strong></p>',
                'variables' => ['customer_name', 'order_number', 'order_date', 'order_total', 'order_status', 'app_name', 'app_url'],
            ],
            [
                'key' => 'order_placed_admin',
                'name' => 'New Order Alert (Admin)',
                'subject' => 'New Order Received #{order_number}',
                'body' => '<p>A new order has been placed on <strong>{app_name}</strong>.</p>
<h3>Order Details</h3>
<table>
  <tr><th>Order Number</th><td><strong>#{order_number}</strong></td></tr>
  <tr><th>Customer</th><td>{customer_name}</td></tr>
  <tr><th>Customer Email</th><td>{customer_email}</td></tr>
  <tr><th>Order Date</th><td>{order_date}</td></tr>
  <tr><th>Order Total</th><td><strong>{order_total}</strong></td></tr>
</table>
<p><a href="{app_url}/admin" class="btn">View in Admin Panel</a></p>',
                'variables' => ['order_number', 'customer_name', 'customer_email', 'order_date', 'order_total', 'app_name', 'app_url'],
            ],
            [
                'key' => 'order_status_changed',
                'name' => 'Order Status Updated (Customer)',
                'subject' => 'Your Order #{order_number} Status Update',
                'body' => '<p>Hi <strong>{customer_name}</strong>,</p>
<p>Your order status has been updated.</p>
<table>
  <tr><th>Order Number</th><td><strong>#{order_number}</strong></td></tr>
  <tr><th>New Status</th><td><strong>{order_status}</strong></td></tr>
</table>
<p>If you have any questions about your order, please contact us.</p>
<p>Thank you for shopping with us!<br><strong>{app_name} Team</strong></p>',
                'variables' => ['customer_name', 'order_number', 'order_status', 'app_name'],
            ],
            [
                'key' => 'password_reset',
                'name' => 'Password Reset',
                'subject' => 'Reset Your {app_name} Password',
                'body' => '<p>Hi <strong>{customer_name}</strong>,</p>
<p>We received a request to reset your password. Click the button below to set a new one:</p>
<p><a href="{reset_url}" class="btn">Reset My Password</a></p>
<p>This link will expire in <strong>{expiry_minutes} minutes</strong>.</p>
<p>If you did not request a password reset, please ignore this email — your password will remain unchanged.</p>
<p><strong>{app_name} Team</strong></p>',
                'variables' => ['customer_name', 'reset_url', 'expiry_minutes', 'app_name'],
            ],
        ];

        foreach ($templates as $template) {
            EmailTemplate::updateOrCreate(['key' => $template['key']], $template);
        }
    }
}
