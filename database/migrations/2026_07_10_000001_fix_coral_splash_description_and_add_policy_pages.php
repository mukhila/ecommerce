<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Fix #1 — Coral Splash Shorts Set: remove "Product details" from description
        DB::table('products')
            ->where('name', 'like', '%Coral Splash%')
            ->each(function ($product) {
                $fixed = preg_replace(
                    '/Product details[:\s]*/i',
                    '',
                    $product->description
                );
                // If description is empty after stripping, use the approved text
                if (empty(strip_tags(trim($fixed)))) {
                    $fixed = '<p>This vibrant co-ord outfit features playful abstract prints that bring a fun and energetic vibe.</p>';
                }
                DB::table('products')
                    ->where('id', $product->id)
                    ->update(['description' => $fixed]);
            });

        // Fix #7 — Add Shipping Policy and Return Policy static pages (if pages table exists)
        if (Schema::hasTable('pages')) {
            $now = now();

            $shippingExists = DB::table('pages')->where('slug', 'shipping-policy')->exists();
            if (!$shippingExists) {
                DB::table('pages')->insert([
                    'title'      => 'Shipping Policy',
                    'slug'       => 'shipping-policy',
                    'content'    => '<h2>Shipping Policy</h2>
<p><strong>Jango Kidswear</strong> is committed to delivering your orders quickly and safely across India.</p>
<ul>
  <li><strong>Standard Delivery:</strong> 5–7 business days</li>
  <li><strong>Express Delivery:</strong> 2–3 business days (where available)</li>
  <li><strong>Free Shipping</strong> on orders above ₹3,000</li>
  <li>Orders are processed within 1–2 business days after payment confirmation.</li>
  <li>Tracking details will be sent to your registered email and phone number once the order is dispatched.</li>
  <li>We ship to all major cities and pin codes across India.</li>
</ul>
<p>For any shipping queries, contact us at <a href="mailto:support@jangokids.com">support@jangokids.com</a> or call +91 98765 43210.</p>',
                    'is_active'  => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            $returnExists = DB::table('pages')->where('slug', 'return-policy')->exists();
            if (!$returnExists) {
                DB::table('pages')->insert([
                    'title'      => 'Return & Refund Policy',
                    'slug'       => 'return-policy',
                    'content'    => '<h2>Return &amp; Refund Policy</h2>
<p>We want you to be completely satisfied with every purchase from <strong>Jango Kidswear</strong>.</p>
<h4>Returns</h4>
<ul>
  <li>Returns are accepted within <strong>7 days</strong> of delivery.</li>
  <li>Items must be unused, unwashed, and in original packaging with tags intact.</li>
  <li>Sale items and customised products are not eligible for return.</li>
</ul>
<h4>How to Initiate a Return</h4>
<ol>
  <li>Email us at <a href="mailto:support@jangokids.com">support@jangokids.com</a> with your order number and reason for return.</li>
  <li>Our team will respond within 24 hours with return instructions.</li>
</ol>
<h4>Refunds</h4>
<ul>
  <li>Approved refunds are processed within <strong>5–7 business days</strong> to your original payment method.</li>
  <li>COD orders will receive a refund via bank transfer.</li>
</ul>
<p>For any queries, call us at +91 98765 43210.</p>',
                    'is_active'  => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('pages')) {
            DB::table('pages')->whereIn('slug', ['shipping-policy', 'return-policy'])->delete();
        }
    }
};
