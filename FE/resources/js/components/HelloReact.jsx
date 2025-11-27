import React from 'react';

export default function HelloReact({ name = 'React' }) {
  return (
    <div className="alert alert-info" role="alert">
      Hello from {name}! This widget is mounted by Vite bundle (JavaScript + JSX).
    </div>
  );
}
