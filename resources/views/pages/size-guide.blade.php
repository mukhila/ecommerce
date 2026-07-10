@extends('layouts.master')

@section('title', 'Size Guide | Jango Kidswear')

@push('styles')
<style>
/* ── Size Guide Layout ──────────────────────────────── */
.size-guide-section { padding: 50px 0 60px; }

.sg-intro { max-width: 720px; margin: 0 auto 40px; text-align: center; }
.sg-intro h2 { font-weight: 700; margin-bottom: 12px; }
.sg-intro p  { color: #666; font-size: 15px; line-height: 1.7; }

/* Tab nav */
.sg-tabs { border-bottom: 2px solid #f0f0f0; margin-bottom: 32px; gap: 4px; flex-wrap: wrap; }
.sg-tabs .nav-link {
    font-size: 14px; font-weight: 600; color: #666;
    padding: 10px 22px; border: none; border-bottom: 3px solid transparent;
    background: none; border-radius: 0; transition: all .2s;
}
.sg-tabs .nav-link.active { color: var(--theme-color, #ff4c3b); border-bottom-color: var(--theme-color, #ff4c3b); }
.sg-tabs .nav-link:hover:not(.active) { color: #333; }

/* Section headings */
.sg-section-title {
    font-size: 16px; font-weight: 700; color: #222;
    border-left: 4px solid var(--theme-color, #ff4c3b);
    padding-left: 12px; margin-bottom: 16px;
}

/* Tables */
.sg-table { width: 100%; border-collapse: collapse; font-size: 14px; }
.sg-table thead th {
    background: var(--theme-color, #ff4c3b); color: #fff;
    padding: 11px 14px; text-align: center; font-weight: 600;
    white-space: nowrap;
}
.sg-table tbody td {
    padding: 10px 14px; text-align: center;
    border-bottom: 1px solid #f0f0f0; color: #444;
}
.sg-table tbody tr:hover { background: #fff8f7; }
.sg-table tbody tr:last-child td { border-bottom: none; }
.sg-table .label-col { text-align: left; font-weight: 600; color: #222; }

/* Info cards */
.sg-tip-card {
    background: #fff8f7; border: 1px solid #fdddd9;
    border-radius: 8px; padding: 20px 24px;
}
.sg-tip-card h5 { font-weight: 700; color: #cc3300; margin-bottom: 10px; }
.sg-tip-card ul  { padding-left: 18px; margin-bottom: 0; }
.sg-tip-card li  { font-size: 14px; color: #555; margin-bottom: 6px; line-height: 1.6; }

/* Measurement steps */
.measure-step { display: flex; gap: 16px; margin-bottom: 20px; align-items: flex-start; }
.measure-num {
    min-width: 36px; height: 36px; border-radius: 50%;
    background: var(--theme-color, #ff4c3b); color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 15px; flex-shrink: 0;
}
.measure-detail h6 { font-weight: 700; margin-bottom: 4px; color: #222; font-size: 15px; }
.measure-detail p  { font-size: 13px; color: #666; margin-bottom: 0; line-height: 1.6; }

/* Note box */
.sg-note {
    background: #fffbea; border: 1px solid #ffe58f;
    border-radius: 6px; padding: 14px 18px; font-size: 13px; color: #7a5900;
}
.sg-note i { color: #f5a623; margin-right: 6px; }

@media (max-width: 576px) {
    .sg-table { font-size: 12px; }
    .sg-table thead th, .sg-table tbody td { padding: 8px 8px; }
}
</style>
@endpush

@section('content')

{{-- Breadcrumb --}}
<div class="breadcrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <div class="page-title"><h2>Size Guide</h2></div>
            </div>
            <div class="col-sm-6">
                <nav aria-label="breadcrumb" class="theme-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Size Guide</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<section class="size-guide-section">
    <div class="container">

        {{-- Intro --}}
        <div class="sg-intro">
            <h2>Kids' Size Guide</h2>
            <p>Finding the perfect fit for your little one is easy with our detailed size charts.
               All measurements are in <strong>centimetres (cm)</strong> unless stated otherwise.
               If your child is between sizes, we recommend sizing up for comfort and room to grow.</p>
        </div>

        {{-- Tab Navigation --}}
        <ul class="nav sg-tabs" id="sizeGuideTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-babies" type="button">
                    Babies (0–24 Months)
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-toddlers" type="button">
                    Toddlers (2–5 Years)
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-kids" type="button">
                    Kids (6–14 Years)
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-measure" type="button">
                    How to Measure
                </button>
            </li>
        </ul>

        <div class="tab-content">

            {{-- ── BABIES ─────────────────────────────────────────── --}}
            <div class="tab-pane fade show active" id="tab-babies" role="tabpanel">
                <div class="row">
                    <div class="col-lg-8 mb-4">
                        <p class="sg-section-title">Clothing Size Chart — Babies (0–24 Months)</p>
                        <div class="table-responsive">
                            <table class="sg-table">
                                <thead>
                                    <tr>
                                        <th>Age</th>
                                        <th>Jango Kidswear Size</th>
                                        <th>Height (cm)</th>
                                        <th>Weight (kg)</th>
                                        <th>Chest (cm)</th>
                                        <th>Waist (cm)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td class="label-col">0–3 Months</td><td>NB</td><td>50–62</td><td>3–6</td><td>40–43</td><td>38–41</td></tr>
                                    <tr><td class="label-col">3–6 Months</td><td>3M</td><td>62–68</td><td>6–8</td><td>43–46</td><td>41–44</td></tr>
                                    <tr><td class="label-col">6–12 Months</td><td>6M</td><td>68–80</td><td>8–11</td><td>46–49</td><td>44–47</td></tr>
                                    <tr><td class="label-col">12–18 Months</td><td>12M</td><td>80–86</td><td>11–13</td><td>49–51</td><td>47–50</td></tr>
                                    <tr><td class="label-col">18–24 Months</td><td>18M</td><td>86–92</td><td>13–15</td><td>51–53</td><td>50–52</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-lg-4 mb-4">
                        <div class="sg-tip-card">
                            <h5><i class="ri-information-line me-1"></i> Buying Tips for Babies</h5>
                            <ul>
                                <li>Babies grow quickly — consider sizing up by 1–2 sizes for longer wear.</li>
                                <li>Stretch fabrics (cotton jersey) are most comfortable for newborns.</li>
                                <li>Look for snap buttons at the bottom for easy nappy changes.</li>
                                <li>Avoid tight neck openings — go for envelope necks or snap collars.</li>
                            </ul>
                        </div>

                        <div class="sg-note mt-3">
                            <i class="ri-error-warning-line"></i>
                            <strong>Note:</strong> Sizes vary slightly by garment type (rompers, bodysuits, sleepsuits). When in doubt, pick the larger size.
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── TODDLERS ────────────────────────────────────────── --}}
            <div class="tab-pane fade" id="tab-toddlers" role="tabpanel">
                <div class="row">
                    <div class="col-lg-8 mb-4">
                        <p class="sg-section-title">Clothing Size Chart — Toddlers (2–5 Years)</p>
                        <div class="table-responsive">
                            <table class="sg-table">
                                <thead>
                                    <tr>
                                        <th>Age</th>
                                        <th>Jango Kidswear Size</th>
                                        <th>Height (cm)</th>
                                        <th>Weight (kg)</th>
                                        <th>Chest (cm)</th>
                                        <th>Waist (cm)</th>
                                        <th>Hip (cm)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td class="label-col">2 Years</td><td>2Y</td><td>92–98</td><td>13–15</td><td>53–55</td><td>51–53</td><td>55–57</td></tr>
                                    <tr><td class="label-col">3 Years</td><td>3Y</td><td>98–104</td><td>15–17</td><td>55–57</td><td>53–55</td><td>57–59</td></tr>
                                    <tr><td class="label-col">4 Years</td><td>4Y</td><td>104–110</td><td>17–19</td><td>57–59</td><td>54–56</td><td>59–61</td></tr>
                                    <tr><td class="label-col">5 Years</td><td>5Y</td><td>110–116</td><td>19–21</td><td>59–61</td><td>55–57</td><td>61–64</td></tr>
                                </tbody>
                            </table>
                        </div>

                        <p class="sg-section-title mt-4">Footwear Size Chart — Toddlers</p>
                        <div class="table-responsive">
                            <table class="sg-table">
                                <thead>
                                    <tr>
                                        <th>Age</th>
                                        <th>India Size</th>
                                        <th>EU Size</th>
                                        <th>UK Size</th>
                                        <th>Foot Length (cm)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td class="label-col">2 Years</td><td>5</td><td>22</td><td>5</td><td>13.5</td></tr>
                                    <tr><td class="label-col">3 Years</td><td>7</td><td>24</td><td>7</td><td>15.0</td></tr>
                                    <tr><td class="label-col">4 Years</td><td>8</td><td>25</td><td>8</td><td>15.5</td></tr>
                                    <tr><td class="label-col">5 Years</td><td>9</td><td>27</td><td>9</td><td>16.5</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-lg-4 mb-4">
                        <div class="sg-tip-card">
                            <h5><i class="ri-information-line me-1"></i> Buying Tips for Toddlers</h5>
                            <ul>
                                <li>Toddlers' waists are still proportionately small — adjustable waistbands are ideal.</li>
                                <li>Choose pull-on trousers and elastic waists for independence dressing.</li>
                                <li>Allow ~1 cm extra in footwear for growth room.</li>
                                <li>Size up if your child is tall for their age.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── KIDS ────────────────────────────────────────────── --}}
            <div class="tab-pane fade" id="tab-kids" role="tabpanel">
                <div class="row">
                    <div class="col-12 mb-4">
                        <p class="sg-section-title">Clothing Size Chart — Kids (6–14 Years)</p>
                        <div class="table-responsive">
                            <table class="sg-table">
                                <thead>
                                    <tr>
                                        <th>Age</th>
                                        <th>Jango Kidswear Size</th>
                                        <th>India Label</th>
                                        <th>Height (cm)</th>
                                        <th>Weight (kg)</th>
                                        <th>Chest (cm)</th>
                                        <th>Waist (cm)</th>
                                        <th>Hip (cm)</th>
                                        <th>Inseam (cm)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td class="label-col">6 Years</td><td>6Y</td><td>S</td><td>116–122</td><td>20–23</td><td>61–64</td><td>56–58</td><td>64–68</td><td>46–49</td></tr>
                                    <tr><td class="label-col">7 Years</td><td>7Y</td><td>S</td><td>122–128</td><td>23–26</td><td>64–66</td><td>57–59</td><td>68–71</td><td>50–53</td></tr>
                                    <tr><td class="label-col">8 Years</td><td>8Y</td><td>M</td><td>128–134</td><td>26–30</td><td>66–68</td><td>58–60</td><td>71–74</td><td>54–57</td></tr>
                                    <tr><td class="label-col">9 Years</td><td>9Y</td><td>M</td><td>134–140</td><td>30–34</td><td>68–70</td><td>60–62</td><td>74–77</td><td>58–61</td></tr>
                                    <tr><td class="label-col">10 Years</td><td>10Y</td><td>L</td><td>140–146</td><td>34–38</td><td>70–73</td><td>61–63</td><td>77–80</td><td>62–65</td></tr>
                                    <tr><td class="label-col">11 Years</td><td>11Y</td><td>L</td><td>146–152</td><td>38–43</td><td>73–76</td><td>63–65</td><td>80–83</td><td>65–68</td></tr>
                                    <tr><td class="label-col">12 Years</td><td>12Y</td><td>XL</td><td>152–158</td><td>43–48</td><td>76–80</td><td>65–67</td><td>83–87</td><td>68–71</td></tr>
                                    <tr><td class="label-col">14 Years</td><td>14Y</td><td>XL</td><td>158–164</td><td>48–55</td><td>80–84</td><td>67–70</td><td>87–92</td><td>71–74</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-lg-6 mb-4">
                        <p class="sg-section-title">Footwear Size Chart — Kids (6–14 Years)</p>
                        <div class="table-responsive">
                            <table class="sg-table">
                                <thead>
                                    <tr>
                                        <th>Age</th>
                                        <th>India Size</th>
                                        <th>EU Size</th>
                                        <th>UK Size</th>
                                        <th>Foot Length (cm)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td class="label-col">6 Years</td><td>1</td><td>33</td><td>1</td><td>20.5</td></tr>
                                    <tr><td class="label-col">7 Years</td><td>2</td><td>34</td><td>2</td><td>21.5</td></tr>
                                    <tr><td class="label-col">8 Years</td><td>3</td><td>35</td><td>3</td><td>22.5</td></tr>
                                    <tr><td class="label-col">9 Years</td><td>4</td><td>36</td><td>4</td><td>23.0</td></tr>
                                    <tr><td class="label-col">10 Years</td><td>5</td><td>37</td><td>5</td><td>23.5</td></tr>
                                    <tr><td class="label-col">12 Years</td><td>6</td><td>38</td><td>6</td><td>24.5</td></tr>
                                    <tr><td class="label-col">14 Years</td><td>7</td><td>40</td><td>7</td><td>25.5</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-lg-6 mb-4">
                        <div class="sg-tip-card">
                            <h5><i class="ri-information-line me-1"></i> Buying Tips for Kids</h5>
                            <ul>
                                <li>Kids grow fast — size up by 1 year for everyday wear.</li>
                                <li>For school uniforms and formal wear, measure chest and waist precisely.</li>
                                <li>Active-wear fits are generally slimmer — check the size chart carefully.</li>
                                <li>For shoes, measure foot length in the evening as feet expand during the day.</li>
                                <li>Always allow at least 1 cm toe room in footwear.</li>
                            </ul>
                        </div>

                        <div class="sg-note mt-3">
                            <i class="ri-error-warning-line"></i>
                            <strong>International Sizes:</strong> Our labels follow Indian standard sizing. EU/UK sizes are approximate equivalents only.
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── HOW TO MEASURE ──────────────────────────────────── --}}
            <div class="tab-pane fade" id="tab-measure" role="tabpanel">
                <div class="row">
                    <div class="col-lg-7 mb-4">
                        <p class="sg-section-title">How to Take Accurate Measurements</p>
                        <p class="text-muted mb-4" style="font-size:14px;">
                            Use a soft measuring tape. Keep the tape snug but not tight. Measure over light clothing or directly on the body for best results.
                        </p>

                        <div class="measure-step">
                            <div class="measure-num">1</div>
                            <div class="measure-detail">
                                <h6>Height</h6>
                                <p>Stand the child against a flat wall without shoes. Place a flat book on top of their head horizontally against the wall and mark the point. Measure from the floor to the mark.</p>
                            </div>
                        </div>

                        <div class="measure-step">
                            <div class="measure-num">2</div>
                            <div class="measure-detail">
                                <h6>Chest</h6>
                                <p>Wrap the measuring tape around the fullest part of the chest, just under the armpits. Keep arms relaxed at the sides. The tape should be parallel to the floor.</p>
                            </div>
                        </div>

                        <div class="measure-step">
                            <div class="measure-num">3</div>
                            <div class="measure-detail">
                                <h6>Waist</h6>
                                <p>Measure around the natural waistline — the narrowest part of the torso, usually just above the belly button. Breathe normally; don't pull the tape too tight.</p>
                            </div>
                        </div>

                        <div class="measure-step">
                            <div class="measure-num">4</div>
                            <div class="measure-detail">
                                <h6>Hip</h6>
                                <p>Measure around the fullest part of the hips and bottom, keeping the tape parallel to the floor. Usually 7–9 cm below the natural waist.</p>
                            </div>
                        </div>

                        <div class="measure-step">
                            <div class="measure-num">5</div>
                            <div class="measure-detail">
                                <h6>Inseam (Trouser Length)</h6>
                                <p>With legs slightly apart, measure from the crotch seam down to the desired trouser length (usually to the ankle bone). A helper makes this easier.</p>
                            </div>
                        </div>

                        <div class="measure-step">
                            <div class="measure-num">6</div>
                            <div class="measure-detail">
                                <h6>Foot Length (for Shoes)</h6>
                                <p>Place the child's foot flat on a piece of paper. Trace around the foot and measure the longest distance from heel to the tip of the longest toe. Measure both feet and use the larger measurement.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5 mb-4">
                        <div class="sg-tip-card mb-3">
                            <h5><i class="ri-ruler-line me-1"></i> Pro Tips</h5>
                            <ul>
                                <li>Measure children in the morning for clothing and in the evening for shoes.</li>
                                <li>Re-measure every 3 months — kids can grow 1–2 cm per month during growth spurts.</li>
                                <li>Write down measurements before shopping online to avoid back-and-forth returns.</li>
                                <li>If between two sizes, always go with the larger one for comfort and longevity.</li>
                            </ul>
                        </div>

                        <div class="sg-note mb-4">
                            <i class="ri-customer-service-line"></i>
                            <strong>Still unsure?</strong> Our team is happy to help.
                            <a href="{{ route('contact') }}" class="fw-600" style="color:var(--theme-color,#ff4c3b);">Contact us</a>
                            and we'll guide you to the right size.
                        </div>

                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h6 class="fw-700 mb-3">Quick Reference — Abbreviations</h6>
                                <table class="table table-sm mb-0" style="font-size:13px;">
                                    <tbody>
                                        <tr><td class="fw-600">NB</td><td>Newborn</td></tr>
                                        <tr><td class="fw-600">M</td><td>Months</td></tr>
                                        <tr><td class="fw-600">Y</td><td>Years</td></tr>
                                        <tr><td class="fw-600">S / M / L / XL</td><td>Small / Medium / Large / Extra Large (India)</td></tr>
                                        <tr><td class="fw-600">EU</td><td>European Union size</td></tr>
                                        <tr><td class="fw-600">UK</td><td>United Kingdom size</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>{{-- end tab-content --}}

        {{-- CTA --}}
        <div class="text-center mt-4 pt-2">
            <p class="text-muted mb-3" style="font-size:14px;">Still have questions about sizing?</p>
            <a href="{{ route('contact') }}" class="btn btn-solid me-2">Contact Support</a>
            <a href="{{ route('faqs') }}" class="btn btn-outline">View FAQs</a>
        </div>

    </div>
</section>

@endsection
