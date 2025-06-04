import { Swiper, SwiperSlide } from 'swiper/react';
import 'swiper/css';
import { motion } from "framer-motion";

const FeaturedProperties = () => {
  const properties = [
    { id: 1, title: "Villa Luxe", price: "€850,000", image: "/villa1.jpg" },
    { id: 2, title: "Appartement Moderne", price: "€420,000", image: "/villa2.jpg" },
    { id: 3, title: "Maison Bord de Mer", price: "€1,200,000", image: "/villa3.jpg" },
  ];

  return (
    <section className="py-16 bg-gray-50">
      <div className="container mx-auto px-4">
        <motion.h2 
          initial={{ opacity: 0 }}
          whileInView={{ opacity: 1 }}
          className="text-3xl font-bold text-center mb-12"
        >
          Nos Biens Exclusifs
        </motion.h2>
        
        <Swiper spaceBetween={30} slidesPerView={1} breakpoints={{ 768: { slidesPerView: 2 }, 1024: { slidesPerView: 3 } }}>
          {properties.map((property) => (
            <SwiperSlide key={property.id}>
              <motion.div whileHover={{ y: -10 }} className="bg-white rounded-xl shadow-lg overflow-hidden">
                <img src={property.image} alt={property.title} className="w-full h-48 object-cover" />
                <div className="p-6">
                  <h3 className="text-xl font-semibold">{property.title}</h3>
                  <p className="text-amber-600 mt-2">{property.price}</p>
                </div>
              </motion.div>
            </SwiperSlide>
          ))}
        </Swiper>
      </div>
    </section>
  );
};

export default FeaturedProperties;