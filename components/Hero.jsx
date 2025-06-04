import { motion } from "framer-motion";
import { TypeAnimation } from 'react-type-animation';

const Hero = () => {
  return (
    <section className="relative h-screen flex items-center justify-center bg-blue-900/50 overflow-hidden">
      {/* Fond flou avec image */}
      <div className="absolute inset-0 bg-[url('/maison-luxe.jpg')] bg-cover bg-center blur-sm" />
      
      {/* Contenu */}
      <motion.div 
        initial={{ opacity: 0, y: 50 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 1 }}
        className="text-center z-10 px-4"
      >
        <h1 className="text-4xl md:text-6xl font-bold text-white mb-4">
          <TypeAnimation
            sequence={[
              'Trouvez la maison de vos rêves',
              1000,
              'Vendez au meilleur prix',
              1000,
            ]}
            speed={50}
            repeat={Infinity}
          />
        </h1>
        <motion.button
          whileHover={{ scale: 1.05 }}
          whileTap={{ scale: 0.95 }}
          className="bg-amber-500 text-white px-8 py-3 rounded-lg font-semibold mt-6"
        >
          Voir les offres
        </motion.button>
      </motion.div>
    </section>
  );
};

export default Hero;