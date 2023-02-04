import React, { useState, useEffect } from "react";

export default function HomeComponent() {
  const [message, setMessage] = useState<string>("");

  useEffect(() => {
    const getMessage = async () => {
      try {
        const response = await fetch("/api/dados", {
          method: "GET",
          headers: {
            "Content-Type": "application/json",
          },
        });

        if (!response.ok) {
          const err = await response.json();
          console.log(err);
          throw new Error(err.message);
        }

        const data = await response.json();
        console.log(data);
        setMessage(data.message);
        
      } catch (error) {
        setMessage(error.message);
        console.log(error);
      }
    }

    const id = setInterval(() => {
      getMessage();
    }, 2000);

    return () => {
      clearInterval(id);
    }
  }, []);

  return (
    <div>
      Componente de Teste (TypeScript + React)
      <p>{message}</p>
    </div>
  );
}