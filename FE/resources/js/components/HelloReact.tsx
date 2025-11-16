import React from 'react';

type Props = { name?: string };

export default function HelloReact({ name = 'React' }: Props) {
  return (
    <div className="alert alert-info" role="alert">
      Hello from {name}! This widget is mounted by Vite bundle.
    </div>
  );
}
