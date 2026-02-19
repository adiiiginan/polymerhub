@extends('en.layouts.app')

@section('title', 'JAYA NIAGA SEMESTA - Home')

@section('content')
    <div class="bg-slate-50 text-slate-800 font-sans overflow-hidden">

        <!-- HERO -->
        <section class="relative">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900"></div>
            <div class="absolute inset-0 opacity-30 bg-[radial-gradient(circle_at_top,white,transparent_60%)]"></div>

            <div class="relative container mx-auto px-6 py-28 text-center text-white">
                <span
                    class="inline-block mb-6 px-5 py-2 text-sm tracking-wide rounded-full bg-white/10 backdrop-blur border border-white/20">
                    Industrial High Performance Material
                </span>

                <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight leading-tight mb-6">
                    Solution Material PTFE <br class="hidden md:block">
                    <span class="text-blue-300">
                        Global Standard</span>
                </h1>

                <p class="text-lg md:text-xl text-blue-200 max-w-3xl mx-auto mb-10">
                    Rulon® delivers precision materials for extreme industrial applications, from high temperatures to
                    aggressive chemical environments.
                </p>

                <a href="#"
                    class="inline-flex items-center gap-2 bg-white text-blue-900 font-semibold px-8 py-4 rounded-full shadow-xl hover:scale-105 transition">
                    Explore Products
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>
        </section>

        <!-- INTRO -->


        <!-- Content -->
        <!-- ================= INTRO VIDEO SECTION ================= -->
        <section class="py-32 bg-slate-50">
            <div class="container mx-auto px-6">
                <div class="grid md:grid-cols-5 gap-16 items-center">

                    <!-- Video -->
                    <div class="md:col-span-3">
                        <div
                            class="aspect-video rounded-2xl overflow-hidden shadow-2xl
                           transform hover:scale-[1.03] transition duration-300">
                            <iframe src="https://www.youtube.com/embed/kRXZR8CFJKw" class="w-full h-full" allowfullscreen>
                            </iframe>
                        </div>
                    </div>

                    <!-- Text -->
                    <div class="md:col-span-2">
                        <h2 class="text-3xl md:text-4xl font-bold text-blue-900 mb-6">

                            Introducing Rulon®
                        </h2>

                        <article class="space-y-5 text-lg leading-relaxed text-gray-700">
                            <p>
                                Rulon® is the trade name for a family of PTFE composite materials manufactured by Omniseal
                                Solutions, a division of Saint-Gobain.
                            </p>

                            <p>
                                During the late 1940s, Robert Rulon-Miller was experimenting with a new material, PTFE
                                (polytetrafluorethylene), for the interior of a new plastic saddle design to ensure smoother
                                function and longer service life. He invented the new formula and named it "Rulon." In 1957,
                                the solution was officially trademarked as Rulon®. This first type of Rulon® material was
                                dubbed "Rulon A" (later replaced by AR).</p>

                            <p>
                                Rulon® has evolved from its original formula to many different grades, each with unique
                                properties designed to serve a variety of applications and industries. </p>
                        </article>
                    </div>

                </div>
            </div>
        </section>

        <!-- ================= KEUNGGULAN SECTION ================= -->
        <section class="py-28 bg-white">
            <div class="container mx-auto px-6">
                <div>
                    <div class="text-center mb-16">
                        <h2 class="text-3xl md:text-4xl font-bold text-blue-900 mb-4">
                            Rulon® Material Advantages
                        </h2>
                        <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                            Designed for superior performance, Rulon® offers an unmatched combination of unique properties.
                        </p>
                    </div>
                    <div class="grid md:grid-cols-2 gap-8 md:gap-6 items-start">

                        <div class="space-y-10" style="margin-left: 150px;">
                            <!-- Feature 1 -->
                            <div class="flex items-start gap-5">
                                <div class="p-3 bg-blue-100 rounded-full">
                                    <svg class="w-6 h-6 text-blue-800" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-lg text-blue-900 mb-1">
                                        Ultra Low Friction</h3>
                                    <p class="text-gray-600">
                                        Reduce energy consumption and component wear.</p>
                                </div>
                            </div>
                            <!-- Feature 2 -->
                            <div class="flex items-start gap-5">
                                <div class="p-3 bg-blue-100 rounded-full">
                                    <svg class="w-6 h-6 text-blue-800" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M4 4v5h5V4H4zm0 12h5v-5H4v5zm10 0h5v-5h-5v5zm0-12h5V4h-5v5z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-lg text-blue-900 mb-1">
                                        Chemical Resistant & Extreme Temperature
                                        Resistance</h3>
                                    <p class="text-gray-600">
                                        Resistant to corrosion and extreme temperature variations from -240°C to 288°C.
                                    </p>
                                </div>
                            </div>
                            <!-- Feature 3 -->
                            <div class="flex items-start gap-5">
                                <div class="p-3 bg-blue-100 rounded-full">
                                    <svg class="w-6 h-6 text-blue-800" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-lg text-blue-900 mb-1">Self-Lubricating</h3>
                                    <p class="text-gray-600">
                                        No need for external lubrication, ideal for clean applications.
                                    </p>
                                </div>
                            </div>
                            <!-- Feature 4 -->
                            <div class="flex items-start gap-5">
                                <div class="p-3 bg-blue-100 rounded-full">
                                    <svg class="w-6 h-6 text-blue-800" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-lg text-blue-900 mb-1">Stabilitas Dimensi</h3>
                                    <p class="text-gray-600">
                                        Maintain shape and size under heavy loads and extreme temperatures.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="w-full sm:max-w-sm md:max-w-md lg:max-w-lg mx-auto">
                            <img src="{{ asset('backend/assets/media/RulonImageHome.jpeg') }}"
                                alt="Rulon materials close-up"
                                class="w-full h-auto object-cover rounded-xl shadow-2xl
               transition-transform duration-300 hover:scale-[1.03]"
                                loading="lazy" />
                        </div>

                    </div>
                </div>
            </div>
        </section>

        <!-- ================= HISTORY SECTION ================= -->
        <!-- History Section -->
        <section class="py-28 bg-slate-50">
            <div class="container mx-auto px-6">
                <!-- Title -->
                <div class="text-center mb-20">
                    <h2 class="text-4xl md:text-5xl font-bold text-blue-900">

                        Long History of Innovation
                    </h2>
                    <p class="text-xl text-gray-600 mt-4 max-w-3xl mx-auto">
                        From the industrial revolution to the birth of high-tech polymer solutions that changed the world.
                </div>

                <!-- Timeline Container -->
                <div class="relative">
                    <!-- Vertical Line -->
                    <div class="hidden md:block absolute inset-y-0 left-1/2 w-0.5 bg-blue-200"></div>

                    <div class="space-y-16 md:space-y-0">
                        <!-- Timeline Item 1: Left -->
                        <div class="relative md:grid md:grid-cols-2 md:gap-12 items-center">
                            <div class="md:pr-12">
                                <div
                                    class="bg-white p-8 rounded-2xl shadow-xl hover:shadow-2xl transition-shadow duration-300">
                                    <h3 class="text-2xl font-semibold text-blue-900 mb-4">
                                        The Beginning of the Industrial Era
                                    </h3>
                                    <article class="space-y-4 text-gray-700 leading-relaxed">
                                        <p>In the late 1800s, textile and cotton mills produced a remarkable array of new
                                            products, ushering in the era of industrialization in the United States and
                                            replacing the old artisan and agricultural way of life. New England, in
                                            particular, experienced a rapid growth of mill towns where several local
                                            inventors and engineers earned a reputation for ingenuity that endures to this
                                            day.</p>
                                        <p>
                                            1.301
                                            One of these pioneers was Ezra Dixon, who came from one of New England's oldest
                                            families.
                                            Interested in machinery from childhood, he spent much of his youth around the
                                            mills of Spencer, Massachusetts;
                                            and nearly forty years of his adult life was employed in all cotton
                                            manufacturing operations (back-boy, cleaner, frame spinner, mule piecer, and
                                            doffer). Dixon was devoted to manufacturing and passionate about solving the
                                            problems that challenged industry owners. After serving in the Civil War, Dixon
                                            ventured to Rhode Island to work in several textile mills, assembling spinning
                                            frames.
                                        </p>
                                    </article>
                                </div>
                            </div>
                            <div class="mt-8 md:mt-0">
                                <div class="w-full max-w-sm md:max-w-md mx-auto">
                                    <img src="{{ asset('backend/assets/media/RulonImageHome.jpeg') }}"
                                        alt="Modern polymer components"
                                        class="w-full object-cover rounded-2xl shadow-2xl
               transition-transform duration-300 hover:scale-[1.03]"
                                        loading="lazy" />
                                </div>
                            </div>
                            <!-- Timeline Dot -->
                            <div
                                class="hidden md:flex absolute top-1/2 -translate-y-1/2 left-1/2 -translate-x-1/2 w-6 h-6 bg-white border-4 border-blue-500 rounded-full items-center justify-center">
                                <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                            </div>
                        </div>

                        <!-- Timeline Item 2: Right -->
                        <div class="relative md:grid md:grid-cols-2 md:gap-12 items-center mt-16">
                            <div class="md:pl-12 md:order-last">
                                <div
                                    class="bg-white p-8 rounded-2xl shadow-xl hover:shadow-2xl transition-shadow duration-300">
                                    <h3 class="text-2xl font-semibold text-blue-900 mb-4">
                                        The Birth of Modern Polymer Solutions
                                    </h3>
                                    <article class="space-y-4 text-gray-700 leading-relaxed">
                                        <p>These mills used saddles on their machines, similar to wooden bands used to
                                            balance
                                            the weight of the
                                            spinning frame.

                                            In 1876, Dixon founded the Dixon Saddle Mills in Providence, moving to Bristol
                                            four years later. Dixon understood that a more advanced saddle design could
                                            significantly increase productivity and soon invented and patented a metal
                                            bearing used in machines for spinning cotton yarn. This bearing became the
                                            global standard for this simple yet essential part. Ironically, Dixon's
                                            relentless dedication to improving the performance of this simple mechanical
                                            component became the basis for the Rulon® fluoropolymer solution you see today –
                                            with the evolution of the Dixon Lubricating Saddle Company into the next
                                            century.

                                            During the late 1940s, Robert Rulon-Miller (who married into the Dixon family
                                            and was President of Dixon Industries Corp.) was experimenting with a new
                                            material, using DuPont® Teflon® (tetrafluoroethylene), for a part in a new
                                            plastic saddle design to ensure smoother function and longer service life. He
                                            discovered a new formula and named it “Rulon.”
                                            This material would have the lowest coefficient of friction, be chemically
                                            resistant, withstand extreme temperatures, and become a critical engineering
                                            element in many applications. In 1957, the solution was officially trademarked
                                            as Rulon®. This first type of Rulon® material was dubbed “Rulon A” (later
                                            replaced by AR).
                                            <br><br>
                                            In the six decades since Rulon® material appeared, first with Dixon Industries
                                            Corp.,
                                            then Furon (which acquired Dixon in 1989), and now Omniseal Solutions,
                                            fluoropolymer solutions have expanded from the original formula to many
                                            different grades, each with unique properties designed to serve a wide range of
                                            applications and industries beyond its industrial heritage.
                                            <br><br>
                                            The precision components we now manufacture include bearings, rings, bands,
                                            basic shapes, wear parts, and molded parts. The material can be machined,
                                            molded, extruded, skinned, stamped, and hot- and cold-formed. Can you guess how
                                            many formulations there are now? The possibilities are endless!
                                        </p>
                                    </article>
                                </div>
                            </div>
                            <div class="mt-8 md:mt-0">
                                <div class="w-full max-w-sm md:max-w-md mx-auto">
                                    <img src="{{ asset('backend/assets/media/RulonImageHome.jpeg') }}"
                                        alt="Modern polymer components"
                                        class="w-full object-cover rounded-2xl shadow-2xl
               transition-transform duration-300 hover:scale-[1.03]"
                                        loading="lazy" />
                                </div>
                            </div>
                            <!-- Timeline Dot -->
                            <div
                                class="hidden md:flex absolute top-1/2 -translate-y-1/2 left-1/2 -translate-x-1/2 w-6 h-6 bg-white border-4 border-blue-500 rounded-full items-center justify-center">
                                <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>



    </div>



    <!-- CTA -->
    <section class="relative overflow-hidden">
        <!-- Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900"></div>
        <div class="absolute inset-0 opacity-25 bg-[radial-gradient(circle_at_bottom,white,transparent_65%)]"></div>

        <div class="relative container mx-auto px-6 py-24 md:py-28 text-center text-white">

            <span
                class="inline-block mb-6 px-5 py-2 text-sm tracking-wide rounded-full bg-white/10 backdrop-blur border border-white/20 text-blue-100">
                Ready for Industrial Excellence
            </span>

            <h2 class="text-3xl md:text-4xl font-semibold tracking-tight leading-snug mb-6 text-white">
                Ready to Optimize Your Industrial Applications?
            </h2>

            <p class="text-lg md:text-xl text-blue-200 max-w-3xl mx-auto mb-10 leading-relaxed">
                Discover the Rulon® polymer solutions designed for high precision, long-lasting stability,
                and maximum performance in extreme working environments.
            </p>

            <div class="flex justify-center gap-4 flex-wrap">
                <a href="#"
                    class="inline-flex items-center gap-2 bg-white text-blue-900 font-medium px-8 py-4 rounded-full shadow-xl hover:scale-105 transition">
                    View Product Catalog
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>


            </div>
        </div>
    </section>



    </div>
@endsection
