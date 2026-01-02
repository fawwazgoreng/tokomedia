import { useRef } from "react";
import { Autoplay, EffectCreative, Navigation, Pagination } from "swiper/modules";
import { Swiper, SwiperSlide } from "swiper/react";

import "swiper/css";
import "swiper/css/navigation";
import { LuChevronLeft, LuChevronRight } from "react-icons/lu";

export const CustomSlider = () => {
  const prevRef = useRef<HTMLButtonElement>(null);
  const nextRef = useRef<HTMLButtonElement>(null);

  return (
    <section className=" mt-10">
      <div className=" relative w-2/3 flex mx-auto h-40 md:h-52 xl:h-64 group ">
          <button ref={prevRef} className=" absolute opacity-0 group-hover:opacity-100 group-hover:-translate-x-2 z-30 px-4 py-2 shadow-2xl translate-x-10 duration-300 max-w-20 max-h-20 bg-white cursor-pointer rounded-full flex items-center justify-center text-black font-bold -left-5 top-1/3">
            <LuChevronLeft className=" w-5 h-8 cursor-pointer"></LuChevronLeft>
          </button>
          <button ref={nextRef} className=" absolute opacity-0 group-hover:opacity-100 group-hover:translate-x-2 z-30 px-4 py-2 shadow-2xl -translate-x-10 duration-300 max-w-20 max-h-20 bg-white cursor-pointer rounded-full flex items-center justify-center text-black font-bold -right-5 top-1/3">
            <LuChevronRight className=" w-5 h-8 cursor-pointer"></LuChevronRight>
          </button>
        <Swiper className=""
          modules={[Navigation , Autoplay , Pagination , EffectCreative]}
          pagination={{
            clickable: true,
            el: ".custom-pagination",
            dynamicBullets : true
          }}
          
          effect="coverflow"
          speed={5000}
          creativeEffect={{
          prev: {

          },
          next: {
          },
          }}
          autoplay={{
            delay : 2500,
            disableOnInteraction : false
          }}
          loop={true}
          // spaceBetween={20}
          slidesPerView={1}
          onInit={(swiper) => {
            const nav = swiper.params.navigation;
            if (nav && typeof nav !== "boolean") {
              nav.prevEl = prevRef.current;
              nav.nextEl = nextRef.current;
              swiper.navigation.init();
              swiper.navigation.update();
            }
          }}
        >
          {[1, 2, 3, 4, 5, 6].map((n) => (
            <SwiperSlide key={n} >
              <div className=" w-full h-full flex items-center justify-center bg-yellow-300">
                {n}
              </div>
            </SwiperSlide>
          ))}
        </Swiper>
        <div className="custom-pagination bg-black" />
      </div>
    </section>
  );
};
