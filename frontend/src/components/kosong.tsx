import { useRef } from "react";
import { Swiper, SwiperSlide } from "swiper/react";
import { Navigation, Pagination } from "swiper/modules";

import "swiper/css";
import "swiper/css/pagination";

export default function CustomSlider() {
  const prevRef = useRef<HTMLButtonElement>(null);
  const nextRef = useRef<HTMLButtonElement>(null);

  return (
    <div className="relative w-full max-w-3xl mx-auto">
      {/* Custom Navigation */}
      <button
        ref={prevRef}
        className="absolute left-0 top-1/2 z-10 -translate-y-1/2 bg-black text-white px-4 py-2 rounded"
      >
        Prev
      </button>

      <button
        ref={nextRef}
        className="absolute right-0 top-1/2 z-10 -translate-y-1/2 bg-black text-white px-4 py-2 rounded"
      >
        Next
      </button>

      <Swiper
        modules={[Navigation, Pagination]}
        spaceBetween={20}
        slidesPerView={1}
        pagination={{  
          clickable: true,
          el: ".custom-pagination",
        }}
        onInit={(swiper) => {
          // inject custom navigation
          // @ts-ignore
          swiper.params.navigation.prevEl = prevRef.current;
          // @ts-ignore
          swiper.params.navigation.nextEl = nextRef.current;
          swiper.navigation.init();
          swiper.navigation.update();
        }}
      >
        <SwiperSlide>
          <div className="h-64 bg-blue-500 text-white flex items-center justify-center text-2xl">
            Slide 1
          </div>
        </SwiperSlide>

        <SwiperSlide>
          <div className="h-64 bg-green-500 text-white flex items-center justify-center text-2xl">
            Slide 2
          </div>
        </SwiperSlide>

        <SwiperSlide>
          <div className="h-64 bg-red-500 text-white flex items-center justify-center text-2xl">
            Slide 3
          </div>
        </SwiperSlide>
      </Swiper>

      {/* Custom Pagination */}
      <div className="custom-pagination flex justify-center gap-2 mt-4" />
    </div>
  );
}
