import React from "react";
import { createRoot } from 'react-dom/client';

import HomeComponent from "./HomeComponent";

const homeContainer = document.getElementById('home-container');
if (homeContainer) {
  const root = createRoot(homeContainer);
  root.render(<HomeComponent />);
}